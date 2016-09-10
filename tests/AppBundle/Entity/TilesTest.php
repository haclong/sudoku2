<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Tiles;

/**
 * Description of TilesTest
 *
 * @author haclong
 */
class TilesTest extends \PHPUnit_Framework_TestCase {
    protected $tileset ;
    protected $tile ;
    
    protected function setUp()
    {
        $this->dispatcher = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcher')
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
        
        $this->tileset = $this->getMockBuilder('AppBundle\Entity\Tiles\Tileset')
                              ->setMethods(array('offsetGet', 'getTile'))
                              ->getMock() ;
        $this->tile = $this->getMockBuilder('AppBundle\Entity\Tile')
                           ->setConstructorArgs(array($this->dispatcher, $this->tileSetEvent, $this->lastPossibilityEvent))
                           ->setMethods(array('getDefinitiveFigure', 'set', 'initialize'))
                           ->getMock() ;
        $this->tileset->method('offsetGet')
                      ->willReturn($this->tile) ;
    }
    public function testConstructor()
    {
        $tiles = new Tiles($this->tileset, $this->tile) ;
        $this->assertEquals($this->tileset, $tiles->getTileset()) ;
    }

    public function testSetTileset()
    {
        $tiles = new Tiles($this->tileset, $this->tile) ;
        $tiles->setTileset(9) ;
        $this->assertEquals(81, count($tiles->getTileset())) ;
        $this->assertInstanceOf('AppBundle\Entity\Tile', $tiles->getTile(0,0)) ;
    }
    
    public function testSetTile()
    {
        $tiles = new Tiles($this->tileset, $this->tile) ;
        $tiles->setTileset(9) ;

        $this->tile->expects($this->once())
                   ->method('set') ;

        $tiles->setTile(0, 0, 3) ;
    }
    
    public function testReload()
    {
        $tiles = new Tiles($this->tileset, $this->tile) ;

        $this->tile->expects($this->exactly(9*9))
                   ->method('initialize') ;
        
        $tiles->reload() ;
    }

    public function testReset()
    {
        $tiles = new Tiles($this->tileset, $this->tile) ;
        $tiles->reset() ;
        $this->assertEquals(0, count($tiles->getTileset())) ;
        $this->assertInstanceOf('AppBundle\Entity\Tiles\Tileset', $tiles->getTileset()) ;
    }
}
