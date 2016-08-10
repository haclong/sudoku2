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
        $grid->solve(true) ;
        $this->assertEquals($grid->getSize(), 9) ;
        $this->assertTrue($grid->isSolved()) ;
        $grid->reset(15) ;
        $this->assertEquals($grid->getSize(), 15) ;
        $this->assertFalse($grid->isSolved()) ;
    }
    
    public function testValue() {
        $grid = new Grid(9) ;
        $array = array(1, 2, 3, 4) ;
        $grid->setTiles($array) ;
        $this->assertEquals($grid->getTiles(), $array) ;
    }
}
