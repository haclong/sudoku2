<?php

namespace Tests\AppBundle\Subscriber;

use AppBundle\Subscriber\RunSolverSubscriber;
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
    
    public function testOnRunSolverTileToSolveWithValue()
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
//    
//    public function testOnRunSolverWithAddingValues()
//    {
//        $event = $this->getMockBuilder('AppBundle\Event\RunSolverEvent')
//                      ->disableOriginalConstructor()
//                      ->getMock() ;
//        $event->method('getTile')->willReturn($this->tileset) ;
//        
//        $this->values->method('getKeyByValue')
//                     ->will($this->onConsecutiveCalls(null, 2)) ;
//
//        $this->values->expects($this->once())
//                     ->method('add') ;
//        $this->groupsService->expects($this->once())
//                            ->method('set')
//                            ->with($this->groups, 2, 2, 3) ;
//        $this->valuesSession->expects($this->once())
//                            ->method('setValues') ;
//        $this->groupsSession->expects($this->once())
//                            ->method('setGroups') ;
//
//        $subscriber = new RunSolverSubscriber($this->groupsSession, $this->valuesSession, $this->groupsService) ;
//        $subscriber->onRunSolver($event) ;
//    }

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
