<?php

namespace Tests\AppBundle\Subscriber;

use AppBundle\Exception\AlreadySetTileException;
use AppBundle\Subscriber\RunSolverSubscriber;
use ArrayObject;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Description of RunSolverSubscriberTest
 *
 * @author haclong
 */
class RunSolverSubscriberTest extends \PHPUnit_Framework_TestCase {
    protected $groupsSession ;
    protected $tilesSession ;
    protected $gridSession ;
    protected $groupsService ;
    protected $groups ;
    protected $tiles ;
    protected $tileToSolve ;

    protected function setUp()
    {
        $this->groups = $this->getMockBuilder('AppBundle\Entity\Groups')->disableOriginalConstructor()->getMock() ;
        $this->tiles = $this->getMockBuilder('AppBundle\Entity\Tiles')->disableOriginalConstructor()->getMock() ;
        $this->grid = $this->getMockBuilder('AppBundle\Entity\Grid')->disableOriginalConstructor()->getMock() ;
        $this->tileToSolve = $this->getMockBuilder('AppBundle\Entity\Tiles\TileToSolve')->getMock() ;
        
        $this->groupsSession = $this->getMockBuilder('AppBundle\Persistence\GroupsSession')
                                    ->disableOriginalConstructor()
                                    ->getMock() ;
        $this->tilesSession = $this->getMockBuilder('AppBundle\Persistence\TilesSession')
                                    ->disableOriginalConstructor()
                                    ->getMock() ;
        $this->gridSession = $this->getMockBuilder('AppBundle\Persistence\GridSession')
                                    ->disableOriginalConstructor()
                                    ->getMock() ;
        $this->groupsService = $this->getMockBuilder('AppBundle\Service\GroupsService')
                                    ->disableOriginalConstructor()
                                    ->setMethods(['set', 'resetGame', 'reloadGame', 'discard'])
                                    ->getMock() ;
        
        $this->groupsSession->method('getGroups')->willReturn($this->groups) ;
        $this->tilesSession->method('getTiles')->willReturn($this->tiles) ;
        $this->gridSession->method('getGrid')->willReturn($this->grid) ;
        $this->tiles->method('getFirstTileToSolve')->willReturn($this->tileToSolve) ;
    }
    
    protected function tearDown()
    {
        $this->groupsService = null ;
        $this->groupsSession = null ;
        $this->tilesSession = null ;
    }

    public function testRunSolverSubscriber()
    {
        $result = $this->commonEventSubscriber('RunSolverEvent', 'onRunSolver') ;
        $this->assertTrue($result) ;
    }
    
    public function testOnRunSolverTileSetNextTile()
    {
        $event = $this->getMockBuilder('AppBundle\Event\RunSolverEvent')
                      ->disableOriginalConstructor()
                      ->getMock() ;
        $this->tileToSolve->method('hasValue')->willReturn(true) ;
        $this->tileToSolve->method('getId')->willReturn('3.2') ;
        $this->tileToSolve->method('getValue')->willReturn(1) ;

        $this->groupsService->expects($this->once())
                            ->method('set')
                            ->with($this->groups, 1, 3, 2) ;
        $this->groupsSession->expects($this->once())
                            ->method('setGroups') ;

        $subscriber = new RunSolverSubscriber($this->groupsSession, $this->tilesSession, $this->gridSession, $this->groupsService) ;
        $subscriber->onRunSolver($event) ;
    }
    
    public function testOnRunSolverMakeAnotherHypothesis()
    {
        $valuesByTile = ['1.2' => [2]] ;
        $event = $this->getMockBuilder('AppBundle\Event\RunSolverEvent')->disableOriginalConstructor()->getMock() ;
        
        $this->tileToSolve->method('hasValue')->willReturn(false) ;
        $this->grid->method('getSize')->willReturn(9) ;
        $this->grid->method('getConfirmedMoves')->willReturn([]) ;
        $this->grid->method('getUnconfirmedMoves')->willReturn([1]) ;
        $this->grid->method('getHypothesis')->willReturn([['id' => '2.0', 'index' => 4]]) ;
        $this->groups->method('getValuesByTile')->willReturn($valuesByTile) ;

        $this->groupsService->expects($this->once())->method('resetGame')->with(9) ;
        $this->groupsService->expects($this->once())->method('reloadGame')->with(9, []) ;
        $this->grid->expects($this->once())->method('storeHypothesis') ;
        $this->groupsService->expects($this->once())->method('set')->with($this->groups, 2, 1, 2, false) ;
        $this->groupsSession->expects($this->once())->method('setGroups') ;

        $subscriber = new RunSolverSubscriber($this->groupsSession, $this->tilesSession, $this->gridSession, $this->groupsService) ;
        $subscriber->onRunSolver($event) ;
    }
    
    public function testOnRunSolverMakeHypothesis()
    {
        $valuesByTile = ['1.2' => [2]] ;
        $event = $this->getMockBuilder('AppBundle\Event\RunSolverEvent')->disableOriginalConstructor()->getMock() ;
        
        $this->tileToSolve->method('hasValue')->willReturn(false) ;
        $this->grid->method('getUnconfirmedMoves')->willReturn([]) ;
        $this->tileToSolve->method('getId')->willReturn('1.2') ;
        $this->groups->method('getValuesByTile')->willReturn($valuesByTile) ;

        $this->groupsService->expects($this->once())->method('set')->with($this->groups, 2, 1, 2, false) ;
        $this->groupsSession->expects($this->once())->method('setGroups') ;

        $subscriber = new RunSolverSubscriber($this->groupsSession, $this->tilesSession, $this->gridSession, $this->groupsService) ;
        $subscriber->onRunSolver($event) ;
    }

    public function testOnRunSolverDiscardHypothesisUnblockNextMove()
    {
        $exception = $this->getMockBuilder('AppBundle\Exception\AlreadySetTileException')->disableOriginalConstructor()->getMock() ;
        $event = $this->getMockBuilder('AppBundle\Event\RunSolverEvent')->disableOriginalConstructor()->getMock() ;
        
        $this->tileToSolve->method('hasValue')->will($this->onConsecutiveCalls(true, true)) ;
        $this->tileToSolve->method('getId')->will($this->onConsecutiveCalls('1.2', '1.1')) ;
        $this->tileToSolve->method('getValue')->will($this->onConsecutiveCalls(0, 2)) ;
        $this->grid->method('getSize')->willReturn(9) ;
        $this->grid->method('getConfirmedMoves')->willReturn([]) ;
        $this->grid->method('getUnconfirmedMoves')->willReturn([['id' => '2.0', 'index' => 1]]) ;
        
        $this->groupsService->expects($this->at(0))->method('set')->with($this->groups, 0, 1, 2)->will($this->throwException($exception)) ;
        $this->groupsService->expects($this->once())->method('resetGame')->with(9) ;
        $this->groupsService->expects($this->once())->method('reloadGame')->with(9, []) ;
        $this->groupsService->expects($this->once())->method('discard')->with($this->groups, 1, 2, 0) ;
        $this->groupsService->expects($this->at(4))->method('set')->with($this->groups, 2, 1, 1, true) ;
        $this->groupsSession->expects($this->once())->method('setGroups') ;

        $subscriber = new RunSolverSubscriber($this->groupsSession, $this->tilesSession, $this->gridSession, $this->groupsService) ;
        $subscriber->onRunSolver($event) ;
    }

    public function testOnRunSolverDiscardHypothesisMakeAnotherHypothesis()
    {
        $exception = $this->getMockBuilder('AppBundle\Exception\AlreadySetTileException')->getMock() ;
        $event = $this->getMockBuilder('AppBundle\Event\RunSolverEvent')->disableOriginalConstructor()->getMock() ;

        $this->grid->method('getUnconfirmedMoves')->willReturn([['id' => '2.0', 'index' => 1]]) ;
        $this->grid->method('getHypothesis')->willReturn([['id' => '0.2', 'index' => 1],
                                                          ['id' => '0.2', 'index' => 2],
                                                          ['id' => '0.2', 'index' => 3]]) ;
        $this->groups->method('getValuesByTile')->willReturn(['0.2' => [1, 2, 3], '0.3' => [0, 1]]) ;
        $this->tileToSolve->method('hasValue')->will($this->onConsecutiveCalls(true, false)) ;
        $this->tileToSolve->method('getId')->will($this->onConsecutiveCalls('1.2')) ;
        $this->tileToSolve->method('getValue')->will($this->onConsecutiveCalls(0)) ;

        $this->groupsService->expects($this->at(0))->method('set')->with($this->groups, 0, 1, 2)->will($this->throwException($exception)) ;
        $this->groupsService->expects($this->at(4))->method('set')->with($this->groups, 0, 0, 3) ;
        $this->groupsSession->expects($this->once())->method('setGroups') ;

        $subscriber = new RunSolverSubscriber($this->groupsSession, $this->tilesSession, $this->gridSession, $this->groupsService) ;
        $subscriber->onRunSolver($event) ;
    }

    protected function commonEventSubscriber($eventName, $method)
    {
        $dispatcher = new EventDispatcher() ;
        $event = $this->getMockBuilder('AppBundle\Event\\'.$eventName)
                                    ->disableOriginalConstructor()
                                    ->getMock() ;
        
        $subscriber = $this->getMockBuilder('AppBundle\Subscriber\RunSolverSubscriber')
                                   ->disableOriginalConstructor()
                                   ->setMethods(array($method))
                                   ->getMock() ;

        $dispatcher->addSubscriber($subscriber) ;

        $subscriber->expects($this->once())
                   ->method($method)
                   ->with($this->equalTo($event));
        $dispatcher->dispatch($event::NAME, $event) ;
        $listeners = $dispatcher->getListeners($event::NAME) ;
        $result = false ;
        foreach($listeners as $listener)
        {
            if($listener[0] instanceof $subscriber) {
                $result = true ;
                continue ;
            }
        }
        return $result ;
    }
}
