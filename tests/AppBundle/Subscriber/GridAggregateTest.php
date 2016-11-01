<?php

namespace Tests\AppBundle\Subscriber;

use AppBundle\Subscriber\GridAggregate;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Description of GridAggregateTest
 *
 * @author haclong
 */
class GridAggregateTest extends \PHPUnit_Framework_TestCase
{
//    protected $dispatcher ;
    protected $session ;
    protected $grid ;
    protected $service ;

    protected function setUp()
    {
        $this->grid = $this->getMockBuilder('AppBundle\Entity\Grid')
                     ->disableOriginalConstructor()
                     ->setMethods(array('getTiles', 'setTiles', 'getSize', 'init', 'reset', 'reload', 'decreaseRemainingTiles'))
                     ->getMock() ;
        $this->grid->method('getSize')
                   ->willReturn(9) ;

        $this->session = $this->getMockBuilder('AppBundle\Persistence\GridSession')
                              ->disableOriginalConstructor()
                              ->getMock() ;
        $this->service = $this->getMockBuilder('AppBundle\Service\SetTileService')
                              ->disableOriginalConstructor()
                              ->getMock() ;
    }
    
    protected function tearDown()
    {
        $this->session = null ;
        $this->grid = null ;
        $this->service = null ;
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
                ->with('gridentity')
                ->willReturn($this->grid) ;
        
        $this->grid->expects($this->once())
                ->method('reset') ;
        $this->session->expects($this->once())
                ->method('setGrid') ;
        
        $gridAggregate = new GridAggregate($this->session, $this->service) ;
        $gridAggregate->onSetGame($event) ;
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
        
        $this->session->method('getGrid')
                ->willReturn($this->grid) ;
        $this->session->expects($this->once())
                ->method('setGrid') ;
        $this->grid->expects($this->once())
                ->method('reset') ;
        
        $gridAggregate = new GridAggregate($this->session, $this->service) ;
        $gridAggregate->onInitGame($event) ;
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
                ->willReturn(array(0 => array(0 => 2, 1 => 4))) ;
        $event = $this->getMockBuilder('AppBundle\Event\LoadGameEvent')
                                    ->setConstructorArgs(array($tiles))
                                    ->getMock() ;
        
        $this->session->method('getGrid')
                ->willReturn($this->grid) ;
        $this->session->expects($this->once())
                ->method('setGrid') ;
        
        $event->expects($this->exactly(3))
              ->method('getTiles')
              ->will($this->returnValue($tiles));
        $this->service->expects($this->atLeastOnce())
                      ->method('setTile') ;
        
        $gridAggregate = new GridAggregate($this->session, $this->service) ;
        $gridAggregate->onLoadGame($event) ;
    }

    public function testRuntimeExceptionExpected()
    {
        $this->setExpectedException(RuntimeException::class) ;
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
        
        $this->session->method('getGrid')
                ->willReturn($this->grid) ;
        
        $gridAggregate = new GridAggregate($this->session, $this->service) ;
        $gridAggregate->onLoadGame($event) ;
    }
    
    public function testReloadGameSubscriber()
    {
        $result = $this->commonEventSubscriber('ReloadGameEvent', 'onReloadGame') ;
        $this->assertTrue($result) ;
    }
    
    public function testOnReloadGame()
    {
        $array = array() ;
        $array[0][3] = 2 ;
        $array[2][5] = 4 ;
        $array[3][2] = 8 ;
        $array[5][3] = 8 ;

        $event = $this->getMockBuilder('AppBundle\Event\ReloadGameEvent')
                                    ->disableOriginalConstructor()
                                    ->getMock() ;
        
        $this->grid->method('getTiles')->willReturn($array) ;
        $this->session->method('getGrid')->willReturn($this->grid) ;

        $this->grid->expects($this->once())->method('reload') ;
        $this->service->expects($this->exactly(4))->method('setTile') ;
        $this->session->expects($this->once())->method('setGrid') ;
       
        $gridAggregate = new GridAggregate($this->session, $this->service) ;
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
                ->method('reset') ;
        $this->session->method('getGrid')
                ->willReturn($this->grid) ;
        $this->session->expects($this->once())
                ->method('setGrid') ;
        $this->grid->expects($this->once())
                ->method('getSize') ;
        $this->grid->expects($this->once())
                ->method('init') ;
        
        $gridAggregate = new GridAggregate($this->session, $this->service) ;
        $gridAggregate->onResetGame($event) ;
    }

    public function testValidatedTileSubscriber()
    {
        $result = $this->commonEventSubscriber('ValidateTileSetEvent', 'onValidatedTile') ;
        $this->assertTrue($result) ;
    }
    
    public function testOnValidatedTile()
    {
        $event = $this->getMockBuilder('AppBundle\Event\ValidateTileSetEvent')
                                    ->disableOriginalConstructor()
                                    ->getMock() ;
        
        $this->session->method('getGrid')
                ->willReturn($this->grid) ;
        $this->grid->expects($this->once())
                   ->method('decreaseRemainingTiles') ;                   
        $this->session->expects($this->once())
                ->method('setGrid') ;
        
        $gridAggregate = new GridAggregate($this->session, $this->service) ;
        $gridAggregate->onValidatedTile($event) ;
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
