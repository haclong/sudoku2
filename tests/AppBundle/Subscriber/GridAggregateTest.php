<?php

namespace Tests\AppBundle\Subscriber;

use AppBundle\Subscriber\GridAggregate;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
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
    protected $attributeBag ;
    protected $service ;

    protected function setUp()
    {
        $mockSessionStorage = new MockArraySessionStorage() ;
        $this->dispatcher = new EventDispatcher() ;
        $attributeBag = new AttributeBag('sudoku') ;
        $attributeBag->setName('sudoku') ;
        $trueSession = new Session($mockSessionStorage) ;
        $trueSession->registerBag($attributeBag) ;
        $this->service = $this->getMockBuilder('AppBundle\Service\SudokuSessionService')
                              ->setConstructorArgs(array($trueSession))
                              ->getMock() ;
        $this->grid = $this->getMockBuilder('AppBundle\Entity\Grid')
                     ->disableOriginalConstructor()
                     ->getMock() ;
    }
    
    protected function tearDown()
    {
        $this->dispatcher = null ;
        $this->service = null ;
        $this->grid = null ;
    }

    public function testGetGridSubscriber()
    {
        $result = $this->commonEventSubscriber('GetGridEvent', 'onGetGrid') ;
        $this->assertTrue($result) ;
    }
    
    public function testOnGetGrid()
    {
        $event = $this->getMockBuilder('AppBundle\Event\GetGridEvent')
                                    ->setConstructorArgs(array($this->grid))
                                    ->getMock() ;
        
        $event->expects($this->once())
              ->method('getGrid')
              ->will($this->returnValue($this->grid));
        
        $gridAggregate = new GridAggregate($this->service) ;
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
        
        $this->service->expects($this->once())
                ->method('resetGrid') ;
        
        $gridAggregate = new GridAggregate($this->service) ;
        $gridAggregate->onResetGrid($event) ;
    }

    protected function commonEventSubscriber($eventName, $method)
    {
        $event = $this->getMockBuilder('AppBundle\Event\\'.$eventName)
                                    ->disableOriginalConstructor()
                                    ->getMock() ;
        
        $subscriber = $this->getMockBuilder('AppBundle\Subscriber\GridAggregate')
                                   ->disableOriginalConstructor()
                                   ->setMethods(array($method))
                                   ->getMock() ;

        $this->dispatcher->addSubscriber($subscriber) ;

        $subscriber->expects($this->once())
                   ->method($method)
                   ->with($this->equalTo($event));
        $this->dispatcher->dispatch($event::NAME, $event) ;
        $listeners = $this->dispatcher->getListeners($event::NAME) ;
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
