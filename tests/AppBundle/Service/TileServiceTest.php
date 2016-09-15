<?php

namespace Tests\AppBundle\Service;

use AppBundle\Exception\InvalidFigureCountException;
use AppBundle\Service\TileService;

/**
 * Description of TileServiceTest
 *
 * @author haclong
 */
class TileServiceTest extends \PHPUnit_Framework_TestCase {
    protected $dispatcher ;
    protected $values ;
    
    protected function setUp()
    {
        $this->dispatcher = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcher')
                                 ->getMock() ;
        $this->values = $this->getMockBuilder('AppBundle\Entity\Values')
                        ->getMock() ;

        $tileLastPossibility = $this->getMockBuilder('AppBundle\Entity\Event\TileLastPossibility')
                                    ->getMock() ;
        $this->lastPossibilityEvent = $this->getMockBuilder('AppBundle\Event\DeduceTileEvent')
                                               ->setConstructorArgs(array($tileLastPossibility))
                                               ->getMock() ;
        $this->lastPossibilityEvent->method('getTile')
                                   ->willReturn($tileLastPossibility) ;

        $tileset = $this->getMockBuilder('AppBundle\Entity\Event\TileSet')
                        ->getMock() ;
        $this->tileSetEvent = $this->getMockBuilder('AppBundle\Event\SetTileEvent')
                                   ->setConstructorArgs(array($tileset))
                                   ->getMock() ;
        $this->tileSetEvent->method('getTile')
                           ->willReturn($tileset) ;
    }
    
    protected function tearDown() {
        $this->dispatcher = null ;
        $this->values = null ;
        $this->tileSetEvent = null ;
        $this->deduceTileEvent = null ;
    }
    
    public function testDiscard()
    {
        $this->values->method('getKeyByValue')
                ->willReturn(2) ;
        $tile = $this->getMockBuilder('AppBundle\Entity\Tile')
                     ->getMock() ;
        $tile->method('getMaybeValues')
                ->willReturn(array(0, 1, 2, 3)) ;
        $tile->method('getSize')
                ->willReturn(4) ;
        
        $tile->expects($this->once())
                ->method('discard')
                ->with($this->EqualTo(2)) ;
        $service = new TileService($this->dispatcher, $this->tileSetEvent, $this->lastPossibilityEvent) ;
        $service->setValues($this->values) ;
        $service->discard($tile, 3) ;
    }
    
    public function testLastPossibilityEventTriggered()
    {
        $this->values->method('getKeyByValue')
                ->willReturn(2) ;
        $tile = $this->getMockBuilder('AppBundle\Entity\Tile')
                     ->getMock() ;
        $tile->method('getMaybeValues')
                ->will($this->onConsecutiveCalls(array(1, 2), array(1), array(1), array(1))) ;
        $tile->method('getDiscardValues')
                ->willReturn(array(0, 3)) ;
        $tile->method('getSize')
                ->willReturn(4) ;

        $tile->expects($this->once())
                ->method('discard')
                ->with($this->EqualTo(2)) ;
        $this->dispatcher->expects($this->once())
                   ->method('dispatch')
                   ->with('tile.lastPossibility', $this->equalTo($this->lastPossibilityEvent));
        $service = new TileService($this->dispatcher, $this->tileSetEvent, $this->lastPossibilityEvent) ;
        $service->setValues($this->values) ;
        $service->discard($tile, 3) ;
    }
    
    public function testSet()
    {
        $this->values->method('getKeyByValue')
                ->willReturn(2) ;
        $tile = $this->getMockBuilder('AppBundle\Entity\Tile')
                     ->getMock() ;
        $tile->method('getDiscardValues')
                ->willReturn(array(0, 1, 3)) ;
        $tile->method('getValue')
                ->willReturn(2) ;
        $tile->method('getSize')
                ->willReturn(4) ;

        $tile->expects($this->once())
                ->method('set')
                ->with($this->EqualTo(2)) ;
        $this->dispatcher->expects($this->once())
                   ->method('dispatch')
                   ->with('tile.set', $this->equalTo($this->tileSetEvent));
        $service = new TileService($this->dispatcher, $this->tileSetEvent, $this->lastPossibilityEvent) ;
        $service->setValues($this->values) ;
        $service->set($tile, 3) ;
    }
    
    public function testInvalidFigureCountExceptionThrown()
    {
        $this->setExpectedException(InvalidFigureCountException::class) ;
        $this->values->method('getKeyByValue')
                ->willReturn(2) ;
        $tile = $this->getMockBuilder('AppBundle\Entity\Tile')
                     ->getMock() ;
        $tile->method('getDiscardValues')
                ->willReturn(array(0, 1, 3)) ;
        $tile->method('getValue')
                ->willReturn(2) ;

        $service = new TileService($this->dispatcher, $this->tileSetEvent, $this->lastPossibilityEvent) ;
        $service->setValues($this->values) ;
        $service->set($tile, 3) ;
    }
}