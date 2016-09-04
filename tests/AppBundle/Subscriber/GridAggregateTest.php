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
                     ->setMethods(array('setTiles', 'getSize', 'init', 'reset'))
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

    public function testGetGridSubscriber()
    {
        $result = $this->commonEventSubscriber('GetGridEvent', 'onGetGrid') ;
        $this->assertTrue($result) ;
    }
    
    public function testOnGetGrid()
    {
        $tiles = $this->getMockBuilder('AppBundle\Entity\Event\TilesLoaded')
                        ->disableOriginalConstructor()
                        ->getMock() ;
        $tiles->method('getSize')
                ->willReturn(9) ;
        $tiles->method('getTiles')
                ->willReturn(array()) ;
        $event = $this->getMockBuilder('AppBundle\Event\GetGridEvent')
                                    ->setConstructorArgs(array($tiles))
                                    ->getMock() ;
        
        $event->expects($this->exactly(2))
              ->method('getTiles')
              ->will($this->returnValue($tiles));
        
        $gridAggregate = new GridAggregate($this->session) ;
        $gridAggregate->onGetGrid($event) ;
    }
    
    public function testResetGridSubscriber()
    {
        $result = $this->commonEventSubscriber('ResetGridEvent', 'onResetGrid') ;
        $this->assertTrue($result) ;
    }
    
    public function testOnResetGrid()
    {
        $event = $this->getMockBuilder('AppBundle\Event\ResetGridEvent')
                                    ->getMock() ;
        
        $this->grid->expects($this->once())
                ->method('reset') ;
        
        $gridAggregate = new GridAggregate($this->session) ;
        $gridAggregate->onResetGrid($event) ;
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
