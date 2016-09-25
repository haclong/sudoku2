<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Grid;
use AppBundle\Exception\InvalidGridSizeException;
use AppBundle\Exception\MaxRemainingTilesLimitException;

/**
 * Description of GridTest
 *
 * @author haclong
 */
class GridTest  extends \PHPUnit_Framework_TestCase 
{
    public function testGrid() {
        $grid = new Grid() ;
        $grid->init(9) ;
        $this->assertFalse($grid->isSolved()) ;
        $this->assertEquals($grid->getSize(), 9) ;
        $this->assertEquals(81, $grid->getRemainingTiles()) ;
        $grid->reload() ;
        $this->assertEquals($grid->getSize(), 9) ;
        $this->assertFalse($grid->isSolved()) ;
    }
    
    public function testValue() {
        $grid = new Grid() ;
        $grid->init(9) ;
        $array = array() ;
        $array[0][3] = 2 ;
        $array[2][5] = 4 ;
        $array[3][2] = 8 ;
        $array[5][3] = 8 ;
        $grid->setTiles($array) ;
        $this->assertEquals($grid->getTiles(), $array) ;
    }
    
    public function testNewGrid() {
        $grid = new Grid() ;
        $grid->init(9) ;
        $array = array() ;
        $array[0][3] = 2 ;
        $array[2][5] = 4 ;
        $array[3][2] = 8 ;
        $array[5][3] = 8 ;
        $grid->setTiles($array) ;
        $grid->reset() ;
        $this->assertEquals(array(), $grid->getTiles()) ;
        $this->assertNull($grid->getSize()) ;
        $this->assertFalse($grid->isSolved()) ;
        $this->assertEquals(-1, $grid->getRemainingTiles()) ;
    }

    public function testDecreaseRemainingTiles() {
        $grid = new Grid() ;
        $grid->init(4) ;
        $this->assertEquals(16, $grid->getRemainingTiles()) ;
        $grid->decreaseRemainingTiles() ;
        $this->assertEquals(15, $grid->getRemainingTiles()) ;
    }

    public function testIncreaseRemainingTiles() {
        $grid = new Grid() ;
        $grid->init(4) ;
        $grid->decreaseRemainingTiles() ;
        $this->assertEquals(15, $grid->getRemainingTiles()) ;
        $grid->increaseRemainingTiles() ;
        $this->assertEquals(16, $grid->getRemainingTiles()) ;
    }

    public function testIncreaseRemainingTilesThrowsException() {
        $this->setExpectedException(MaxRemainingTilesLimitException::class) ;
                
        $grid = new Grid() ;
        $grid->init(4) ;
        $grid->increaseRemainingTiles() ;
    }

    public function testSolved() {
        $grid = new Grid() ;
        $grid->init(4) ;
        $this->assertFalse($grid->isSolved()) ;
        for($i=0; $i<16; $i++)
        {
            $grid->decreaseRemainingTiles() ;
        }
        $this->assertTrue($grid->isSolved()) ;
    }

    public function testInvalidGridSizeException()
    {
        $this->setExpectedException(InvalidGridSizeException::class) ;
        $grid = new Grid() ;
        $grid->init(3) ;
    }
}
