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
    }
    public function testConstructor()
    {
        $tiles = new Tiles($this->tileset) ;
        $this->assertEquals($this->tileset, $tiles->getTileset()) ;
    }

    public function testSetTileset()
    {
        $tiles = new Tiles($this->tileset) ;
        $tiles->init(9) ;
        $this->assertEquals(81, count($tiles->getTileset())) ;
    }
    
//    public function testReload()
//    {
//        $tiles = new Tiles($this->tileset, $this->tile) ;
//
//        $this->tile->expects($this->exactly(9*9))
//                   ->method('initialize') ;
//        
//        $tiles->reload() ;
//    }
//
    public function testReset()
    {
        $tiles = new Tiles($this->tileset) ;
        $tiles->reset() ;
        $this->assertEquals(0, count($tiles->getTileset())) ;
        $this->assertInstanceOf('AppBundle\Entity\Tiles\Tileset', $tiles->getTileset()) ;
    }
}
