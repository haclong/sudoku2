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
    
    protected function setUp()
    {
        $this->tileset = $this->getMockBuilder('AppBundle\Entity\Tiles\Tileset')
                              ->setMethods(array('offsetGet'))
                              ->getMock() ;
        $this->tileset->method('offsetGet')
                      ->willReturn(3) ;
        $this->tiletosolve = $this->getMockBuilder('AppBundle\Entity\Tiles\TileToSolve')
                                  ->disableOriginalClone()
                                  ->setMethods(array('getId', '__toString'))
                                  ->getMock() ;
    }
    public function testConstructor()
    {
        $tiles = new Tiles($this->tileset, $this->tiletosolve) ;
        $this->assertEquals($this->tileset, $tiles->getTileset()) ;
        $this->assertEquals(array(), $tiles->getTilesToSolve()) ;
    }

    public function testInit()
    {
        $tiles = new Tiles($this->tileset, $this->tiletosolve) ;
        $tiles->init(9) ;
        
        $count = 0 ;
        foreach($tiles->getTileset() as $tile)
        {
            if(!is_null($tile))
            {
                $count++ ;
            }
        }
        $this->assertEquals(81, count($tiles->getTileset())) ;
        $this->assertEquals(0, $count) ;
        $this->assertEquals(9, $tiles->getSize()) ;
        $this->assertEquals(81, count($tiles->getTilesToSolve())) ;
    }
    
    public function testReload()
    {
        $grid = $this->getMockBuilder('AppBundle\Entity\Grid')->getMock() ;
        $grid->method('getSize')->willReturn(9) ;
        $tiles = new Tiles($this->tileset, $this->tiletosolve) ;
        $tiles->init(9) ;
        $tiles->set(3, 5, 4) ;
        $tiles->set(3, 2, 1) ;
        $tiles->reload($grid) ;

        $count = 0 ;
        foreach($tiles->getTileset() as $tile)
        {
            if(!is_null($tile))
            {
                $count++ ;
            }
        }
        $this->assertEquals(0, $count) ;
        $this->assertEquals(81, count($tiles->getTilesToSolve())) ;
    }

    public function testReset()
    {
        $tiles = new Tiles($this->tileset, $this->tiletosolve) ;
        $tiles->reset() ;
        $this->assertEquals(0, count($tiles->getTileset())) ;
        $this->assertInstanceOf('AppBundle\Entity\Tiles\Tileset', $tiles->getTileset()) ;
        $this->assertEquals(0, count($tiles->getTilesToSolve())) ;
    }
    
    public function testGetTile()
    {
        $tileset = $this->getMockBuilder('AppBundle\Entity\Tiles\Tileset')
                              ->getMock() ;
        $tileset->expects($this->once())
              ->method('offsetGet')
              ->with('3.5') ;
        $tiles = new Tiles($tileset, $this->tiletosolve) ;
        $tiles->getTile(3, 5) ;
    }
    
    public function testSetCallsTilesetOffsetSet()
    {
        $tileset = $this->getMockBuilder('AppBundle\Entity\Tiles\Tileset')
                              ->getMock() ;
        $tileset->expects($this->once())
              ->method('offsetSet')
              ->with('3.5', 4) ;
        $tiles = new Tiles($tileset, $this->tiletosolve) ;
        $tiles->set(3, 5, 4) ;
    }
    
    public function testSetSetsTile()
    {
        $tiles = new Tiles($this->tileset, $this->tiletosolve) ;
        $tiles->init(9) ;
        $tiles->getTilesToSolve()[0]->method('getId')->willReturn('3.5') ;
        $tiles->getTilesToSolve()[1]->method('getId')->willReturn('3.2') ;
        
        $tiles->set(3, 5, 4) ;
        $tiles->set(3, 2, 1) ;
        
        $count = 0 ;
        foreach($tiles->getTileset() as $tile)
        {
            if(!is_null($tile))
            {
                $count++ ;
            }
        }

        $this->assertEquals(2, $count) ;
        $this->assertEquals(79, count($tiles->getTilesToSolve())) ;
    }
    
    public function testFirstTileToSolve()
    {
        $tiles = new Tiles($this->tileset, $this->tiletosolve) ;
        $tiles->init(9) ;
        $tiles->getFirstTileToSolve()->method('__toString')->willReturn('0.0') ;
        $this->assertEquals('0.0', $tiles->getFirstTileToSolve()) ;
    }
    
    public function testPriorizeTileToSolve()
    {
        $lastPossibilityTile = $this->getMockBuilder('AppBundle\Entity\Event\TileLastPossibility')
                              ->getMock() ;
        $lastPossibilityTile->method('getRow')->willReturn(8) ;
        $lastPossibilityTile->method('getCol')->willReturn(8) ;
        $lastPossibilityTile->method('getValue')->willReturn(2) ;
        
        $tiles = new Tiles($this->tileset, $this->tiletosolve) ;
        $tiles->init(9) ;
        $tiles->priorizeTileToSolve($lastPossibilityTile) ;
        $tiles->getFirstTileToSolve()->method('__toString')->willReturn('8.8') ;
        
        $this->assertEquals('8.8', $tiles->getFirstTileToSolve()) ;
//        $counted_values = array_count_values($tiles->getTilesToSolve()) ;
//        $this->assertEquals(1, $counted_values['8.8']) ;
    }
    
    public function testGetIndexToSet()
    {
        $lastPossibilityTile = $this->getMockBuilder('AppBundle\Entity\Event\TileLastPossibility')
                              ->getMock() ;
        $lastPossibilityTile->method('getRow')->willReturn(8) ;
        $lastPossibilityTile->method('getCol')->willReturn(8) ;
        $lastPossibilityTile->method('getValue')->willReturn(2) ;

        $tiles = new Tiles($this->tileset, $this->tiletosolve) ;
        $tiles->init(9) ;
        $tiles->priorizeTileToSolve($lastPossibilityTile) ;
        
        $this->assertEquals(2, $tiles->getIndexToSet('8.8')) ;
        $this->assertNull($tiles->getIndexToSet('0.0')) ;
    }
}
