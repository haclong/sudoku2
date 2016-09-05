<?php

namespace Tests\AppBundle\Subscriber;

use AppBundle\Subscriber\GridAggregate;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Description of GridAggregateTest
 *
 * @author haclong
 */
class GridAggregateTest extends \PHPUnit_Framework_TestCase
{
    protected $dispatcher ;
    protected $session ;
    protected $grid ;

    protected function setUp()
    {
        $mockSessionStorage = new MockArraySessionStorage() ;
        $this->session = new Session($mockSessionStorage) ;
        $this->grid = $this->getMockBuilder('AppBundle\Entity\Grid')
                     ->disableOriginalConstructor()
                     ->setMethods(array('setTiles', 'getSize', 'init', 'reset', 'newGrid'))
                     ->getMock() ;
        $this->grid->method('getSize')
                ->willReturn(9) ;
        $this->session->set('grid', $this->grid) ;
//        $this->service = $this->getMockBuilder('AppBundle\Service\SudokuSessionService')
//                              //->setConstructorArgs(array($trueSession))
//                              //->disableOriginalConstructor()
//                              ->getMock() ;
//        $this->service->method('setSession')
//                    ->with($this->equalTo($trueSession))
//                    ->will($this->returnSelf());
//        $this->service->method('getGridFromSession')
//                    ->willReturn($this->grid) ;
    }
    
    protected function tearDown()
    {
//        $this->dispatcher = null ;
        $this->session = null ;
        $this->grid = null ;
    }

    public function testChooseGameSubscriber()
    {
        $result = $this->commonEventSubscriber('ChooseGameEvent', 'onChooseGame') ;
        $this->assertTrue($result) ;
    }

    public function testOnChooseGame()
    {
        $size = $this->getMockBuilder('AppBundle\Entity\Event\GridSize')
                        ->disableOriginalConstructor()
                        ->getMock() ;
        $size->method('get')
                ->willReturn(9) ;
        $event = $this->getMockBuilder('AppBundle\Event\ChooseGameEvent')
                                    ->setConstructorArgs(array($size))
                                    ->getMock() ;
        $event->method('getGridSize')
                ->willReturn($size) ;
        
        $this->grid->expects($this->once())
                ->method('newGrid') ;
        
        $gridAggregate = new GridAggregate($this->session) ;
        $gridAggregate->onChooseGame($event) ;
    }

    public function testLoadGameSubscriber()
    {
        $result = $this->commonEventSubscriber('LoadGameEvent', 'onLoadGame') ;
        $this->assertTrue($result) ;
    }
    
    public function testOnLoadGame()
    {
        $tiles = $this->getMockBuilder('AppBundle\Entity\Event\TilesLoaded')
                        ->disableOriginalConstructor()
                        ->getMock() ;
        $tiles->method('getSize')
                ->willReturn(9) ;
        $tiles->method('getTiles')
                ->willReturn(array()) ;
        $event = $this->getMockBuilder('AppBundle\Event\LoadGameEvent')
                                    ->setConstructorArgs(array($tiles))
                                    ->getMock() ;
        
        $event->expects($this->exactly(2))
              ->method('getTiles')
              ->will($this->returnValue($tiles));
        
        $gridAggregate = new GridAggregate($this->session) ;
        $gridAggregate->onLoadGame($event) ;
    }

    public function testRuntimeExceptionExpected()
    {
        $this->setExpectedException(\RuntimeException::class) ;
        $tiles = $this->getMockBuilder('AppBundle\Entity\Event\TilesLoaded')
                        ->disableOriginalConstructor()
                        ->getMock() ;
        $tiles->method('getSize')
                ->willReturn(8) ;
        $tiles->method('getTiles')
                ->willReturn(array()) ;
        $event = $this->getMockBuilder('AppBundle\Event\LoadGameEvent')
                                    ->setConstructorArgs(array($tiles))
                                    ->getMock() ;
        $event->method('getTiles')
              ->willReturn($tiles);
        
        
        $gridAggregate = new GridAggregate($this->session) ;
        $gridAggregate->onLoadGame($event) ;
    }
    
    public function testReloadGameSubscriber()
    {
        $result = $this->commonEventSubscriber('ReloadGameEvent', 'onReloadGame') ;
        $this->assertTrue($result) ;
    }
    
    public function testOnReloadGame()
    {
        $event = $this->getMockBuilder('AppBundle\Event\ReloadGameEvent')
                                    ->getMock() ;
        
        $this->grid->expects($this->once())
                ->method('reset') ;
        
        $gridAggregate = new GridAggregate($this->session) ;
        $gridAggregate->onReloadGame($event) ;
    }

    public function testResetGameSubscriber()
    {
        $result = $this->commonEventSubscriber('ResetGameEvent', 'onResetGame') ;
        $this->assertTrue($result) ;
    }
    
    public function testOnResetGame()
    {
        $event = $this->getMockBuilder('AppBundle\Event\ResetGameEvent')
                                    ->getMock() ;
        
        $this->grid->expects($this->once())
                ->method('newGrid') ;
        
        $gridAggregate = new GridAggregate($this->session) ;
        $gridAggregate->onResetGame($event) ;
    }
   
    protected function commonEventSubscriber($eventName, $method)
    {
        $dispatcher = new EventDispatcher() ;
        $event = $this->getMockBuilder('AppBundle\Event\\'.$eventName)
                                    ->disableOriginalConstructor()
                                    ->getMock() ;
        
        $subscriber = $this->getMockBuilder('AppBundle\Subscriber\GridAggregate')
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
