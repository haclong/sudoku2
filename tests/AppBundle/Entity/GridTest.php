<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Grid ;

/**
 * Description of GridTest
 *
 * @author haclong
 */
class GridTest  extends \PHPUnit_Framework_TestCase 
{
    public function testGrid() {
        $grid = new Grid(9) ;
        $this->assertFalse($grid->isSolved()) ;
//        $grid->solve(true) ;
        $this->assertEquals($grid->getSize(), 9) ;
        $this->assertEquals(81, $grid->getRemainingTiles()) ;
//        $this->assertTrue($grid->isSolved()) ;
        $grid->reset() ;
        $this->assertEquals($grid->getSize(), 9) ;
        $this->assertFalse($grid->isSolved()) ;
    }
    
    public function testValue() {
        $grid = new Grid(9) ;
        $array = array() ;
        $array[0][3] = 2 ;
        $array[2][5] = 4 ;
        $array[3][2] = 8 ;
        $array[5][3] = 8 ;
        $grid->setTiles($array) ;
        $this->assertEquals($grid->getTiles(), $array) ;
    }
    
    public function testDecreaseRemainingTiles() {
        $grid = new Grid(4) ;
        $this->assertEquals(16, $grid->getRemainingTiles()) ;
        $grid->decreaseRemainingTiles() ;
        $this->assertEquals(15, $grid->getRemainingTiles()) ;
    }
            
}
