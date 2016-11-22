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
        $this->assertEquals(9, $grid->getSize()) ;
        $this->assertEquals(81, $grid->getRemainingTiles()) ;
        $grid->reload() ;
        $this->assertEquals(9, $grid->getSize()) ;
        $this->assertFalse($grid->isSolved()) ;
        $this->assertEquals(0, count($grid->getConfirmedMoves())) ;
        $this->assertEquals(0, count($grid->getUnconfirmedMoves())) ;
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
        $this->assertEquals($array, $grid->getTiles()) ;
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
    
    public function testStoreConfirmedMove()
    {
        $expectedMove = [4 => [2 => 3]] ;
        $grid = new Grid() ;
        $this->assertEquals(0, count($grid->getConfirmedMoves())) ;
        $this->assertEquals(0, count($grid->getUnconfirmedMoves())) ;
        $grid->storeMove(4, 2, 3, true) ;
        $this->assertEquals($expectedMove, $grid->getConfirmedMoves()) ;
        $this->assertEquals(0, count($grid->getUnconfirmedMoves())) ;
    }
    
    public function testStoreUnconfirmedMove()
    {
        $expectedMove[] = ['id' => '4.2', 'index' => 3] ;
        $grid = new Grid() ;
        $this->assertCount(0, $grid->getConfirmedMoves()) ;
        $this->assertCount(0, $grid->getUnconfirmedMoves()) ;
        $grid->storeMove(4, 2, 3, false) ;
        $this->assertCount(0, $grid->getConfirmedMoves()) ;
        $this->assertEquals($expectedMove, $grid->getUnconfirmedMoves()) ;
    }
    
    public function testStoreHypothesis()
    {
        $grid = new Grid() ;
        $this->assertEmpty($grid->getHypothesis()) ;
        $grid->storeHypothesis('hello') ;
        $this->assertEquals(['hello'], $grid->getHypothesis()) ;
    }
}
