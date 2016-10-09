<?php

namespace Tests\AppBundle\Subscriber;

use AppBundle\Subscriber\GroupsAggregate;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Description of GroupsAggregateTest
 *
 * @author haclong
 */
class GroupsAggregateTest extends \PHPUnit_Framework_TestCase {
    protected $session ;
    protected $groups ;
    
    protected function setUp()
    {
        $this->groups = $this->getMockBuilder('AppBundle\Entity\Groups')
                            ->disableOriginalConstructor()
                            ->getMock() ;

        $this->session = $this->getMockBuilder('AppBundle\Persistence\GroupsSession')
                              ->disableOriginalConstructor()
                              ->getMock() ;
    }
    
    protected function tearDown()
    {
        $this->session = null ;
        $this->groups = null ;
    }

    public function testSetGameSubscriber()
    {
        $result = $this->commonEventSubscriber('SetGameEvent', 'onSetGame') ;
        $this->assertTrue($result) ;
    }

    public function testOnSetGame()
    {
        $event = $this->getMockBuilder('AppBundle\Event\SetGameEvent')
                                    ->disableOriginalConstructor()
                                    ->getMock() ;
        $event->method('getEntity')
              ->with('groupsentity')
              ->willReturn($this->groups) ;
        
        $this->groups->expects($this->once())
                ->method('reset') ;
        $this->session->expects($this->once())
                ->method('setGroups') ;
        
        $groupsAggregate = new GroupsAggregate($this->session) ;
        $groupsAggregate->onSetGame($event) ;
    }

    public function testInitGameSubscriber()
    {
        $result = $this->commonEventSubscriber('InitGameEvent', 'onInitGame') ;
        $this->assertTrue($result) ;
    }

    public function testOnInitGame()
    {
        $size = $this->getMockBuilder('AppBundle\Entity\Event\GridSize')
                        ->disableOriginalConstructor()
                        ->getMock() ;
        $size->method('get')
                ->willReturn(9) ;
        $event = $this->getMockBuilder('AppBundle\Event\InitGameEvent')
                                    ->setConstructorArgs(array($size))
                                    ->getMock() ;
        $event->method('getGridSize')
                ->willReturn($size) ;
        
        $this->session->method('getGroups')
                ->willReturn($this->groups) ;
        $this->session->expects($this->once())
                ->method('setGroups') ;
        $this->groups->expects($this->once())
                ->method('reset') ;
        $this->groups->expects($this->once())
                ->method('init')
                ->with($this->equalTo($size->get())) ;
        
        $groupsAggregate = new GroupsAggregate($this->session) ;
        $groupsAggregate->onInitGame($event) ;
    }
    
    public function testReloadGameSubscriber()
    {
        $result = $this->commonEventSubscriber('ReloadGameEvent', 'onReloadGame') ;
        $this->assertTrue($result) ;
    }
    
    public function testOnReloadGame()
    {
        $grid = $this->getMockBuilder('AppBundle\Entity\Grid')
                        ->getMock() ;
        $event = $this->getMockBuilder('AppBundle\Event\ReloadGameEvent')
                                    ->disableOriginalConstructor()
                                    ->getMock() ;
        $event->method('getGrid')
              ->willReturn($grid) ;
        $this->groups->expects($this->once())
                ->method('reload')
                ->with($this->equalTo($grid)) ;
        $this->session->method('getGroups')
                ->willReturn($this->groups) ;
        $this->session->expects($this->once())
                ->method('setGroups') ;
        
        $groupsAggregate = new GroupsAggregate($this->session) ;
        $groupsAggregate->onReloadGame($event) ;
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
        
        $this->groups->expects($this->once())
                ->method('reset') ;
        $this->session->method('getGroups')
                ->willReturn($this->groups) ;
        $this->session->expects($this->once())
                ->method('setGroups') ;
        $this->groups->expects($this->once())
                ->method('init') ;
        
        $groupsAggregate = new GroupsAggregate($this->session) ;
        $groupsAggregate->onResetGame($event) ;
    }
   
    protected function commonEventSubscriber($eventName, $method)
    {
        $dispatcher = new EventDispatcher() ;
        $event = $this->getMockBuilder('AppBundle\Event\\'.$eventName)
                                    ->disableOriginalConstructor()
                                    ->getMock() ;
        
        $subscriber = $this->getMockBuilder('AppBundle\Subscriber\GroupsAggregate')
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
