<?php

namespace Tests\AppBundle\Entity\Tiles;

use AppBundle\Entity\Tiles\TileToSolve;

/**
 * Description of TileToSolveTest
 *
 * @author haclong
 */
class TileToSolveTest extends \PHPUnit_Framework_TestCase {
    public function testGetId()
    {
        $tile = new TileToSolve() ;
        $id = 3 ;
        $tile->setId($id) ;
        $this->assertEquals($id, $tile->getId()) ;
    }
    
    public function testGetValue()
    {
        $tile = new TileToSolve() ;
        $value = '3.2' ;
        $tile->setValue($value) ;
        $this->assertEquals($value, $tile->getValue()) ;
    }
    
    public function testToString()
    {
        $tile = new TileToSolve() ;
        $id = 3 ;
        $tile->setId($id) ;
        $string = 'Tile id is %s';
        $printedId = sprintf($string, $tile) ;
        $this->assertEquals('Tile id is 3', $printedId) ;
    }
}
