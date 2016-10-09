<?php

namespace Tests\AppBundle\Subscriber;

use AppBundle\Subscriber\SetTileSubscriber;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Description of SetTileSubscriberTest
 *
 * @author haclong
 */
class SetTileSubscriberTest extends \PHPUnit_Framework_TestCase {
    protected $groupsSession ;
    protected $valuesSession ;
    protected $groupsService ;
    protected $groups ;
    protected $values ;
    protected $tileset ;

    protected function setUp()
    {
        $this->groups = $this->getMockBuilder('AppBundle\Entity\Groups')->getMock() ;
        $this->values = $this->getMockBuilder('AppBundle\Entity\Values')->getMock() ;
        $this->tileset = $this->getMockBuilder('AppBundle\Entity\Event\TileSet')->getMock() ;
        
        $this->groupsSession = $this->getMockBuilder('AppBundle\Persistence\GroupsSession')
                                    ->disableOriginalConstructor()
                                    ->getMock() ;
        $this->valuesSession = $this->getMockBuilder('AppBundle\Persistence\ValuesSession')
                                    ->disableOriginalConstructor()
                                    ->getMock() ;
        $this->groupsService = $this->getMockBuilder('AppBundle\Service\GroupsService')
                                    ->disableOriginalConstructor()
                                    ->getMock() ;
        
        $this->groupsSession->method('getGroups')->willReturn($this->groups) ;
        $this->valuesSession->method('getValues')->willReturn($this->values) ;
        $this->tileset->method('getValue')->willReturn(8) ;
        $this->tileset->method('getRow')->willReturn(2) ;
        $this->tileset->method('getCol')->willReturn(3) ;
    }
    
    protected function tearDown()
    {
        $this->groupsService = null ;
        $this->groupsSession = null ;
        $this->valuesSession = null ;
    }

    public function testSetTileSubscriber()
    {
        $result = $this->commonEventSubscriber('SetTileEvent', 'onSetTile') ;
        $this->assertTrue($result) ;
    }
    
    public function testOnSetTileNotAddingValues()
    {
        $event = $this->getMockBuilder('AppBundle\Event\SetTileEvent')
                      ->disableOriginalConstructor()
                      ->getMock() ;
        $event->method('getTile')->willReturn($this->tileset) ;
        
        $this->values->method('getKeyByValue')
                     ->willReturn(2) ;

        $this->values->expects($this->never())
                     ->method('add') ;
        $this->groupsService->expects($this->once())
                            ->method('set')
                            ->with($this->groups, 2, 2, 3) ;
        $this->valuesSession->expects($this->once())
                            ->method('setValues') ;
        $this->groupsSession->expects($this->once())
                            ->method('setGroups') ;

        $subscriber = new SetTileSubscriber($this->groupsSession, $this->valuesSession, $this->groupsService) ;
        $subscriber->onSetTile($event) ;
    }
    
    public function testOnSetTileWithAddingValues()
    {
        $event = $this->getMockBuilder('AppBundle\Event\SetTileEvent')
                      ->disableOriginalConstructor()
                      ->getMock() ;
        $event->method('getTile')->willReturn($this->tileset) ;
        
        $this->values->method('getKeyByValue')
                     ->will($this->onConsecutiveCalls(null, 2)) ;

        $this->values->expects($this->once())
                     ->method('add') ;
        $this->groupsService->expects($this->once())
                            ->method('set')
                            ->with($this->groups, 2, 2, 3) ;
        $this->valuesSession->expects($this->once())
                            ->method('setValues') ;
        $this->groupsSession->expects($this->once())
                            ->method('setGroups') ;

        $subscriber = new SetTileSubscriber($this->groupsSession, $this->valuesSession, $this->groupsService) ;
        $subscriber->onSetTile($event) ;
    }

    protected function commonEventSubscriber($eventName, $method)
    {
        $dispatcher = new EventDispatcher() ;
        $event = $this->getMockBuilder('AppBundle\Event\\'.$eventName)
                                    ->disableOriginalConstructor()
                                    ->getMock() ;
        
        $subscriber = $this->getMockBuilder('AppBundle\Subscriber\SetTileSubscriber')
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
