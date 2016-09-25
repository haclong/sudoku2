<?php

namespace Tests\AppBundle\Utils;

use AppBundle\Utils\SudokuSession;

/**
 * Description of SudokuSessionTest
 *
 * @author haclong
 */
class SudokuSessionTest extends \PHPUnit_Framework_TestCase {
    /**
     * @runInSeparateProcess
     */
    public function testClear()
    {
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                        ->getMock() ;
        
        $grid = $this->getMockBuilder('AppBundle\Entity\Grid')
                     ->getMock() ;
        $values = $this->getMockBuilder('AppBundle\Entity\Values')
                     ->getMock() ;
        $tiles = $this->getMockBuilder('AppBundle\Entity\Tiles')
                    ->disableOriginalConstructor()
                    ->getMock() ;
        
        $session->expects($this->once())
                ->method('clear') ;
        $sudokuSession = new SudokuSession($session) ;
        $sudokuSession->setGrid($grid) ;
        $sudokuSession->setValues($values) ;
        $sudokuSession->setTiles($tiles) ;
        $sudokuSession->clear() ;
        $this->assertNull($sudokuSession->getGrid()) ;
        $this->assertNull($sudokuSession->getValues()) ;
        $this->assertNull($sudokuSession->getTiles()) ;
    }
    
    public function testGetGridCallsSessionGet()
    {
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                     ->getMock() ;
        $session->expects($this->once())
                ->method('get')
                ->with($this->equalTo('grid')) ;
        $sudokuSession = new SudokuSession($session) ;
        $sudokuSession->getGrid() ;
    }
        
    public function testGetGridReturnsGrid()
    {
        $grid = $this->getMockBuilder('AppBundle\Entity\Grid')
                     ->getMock() ;
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                     ->getMock() ;
        $session->expects($this->once())
                ->method('get')
                ->will($this->returnValue($grid));
        $sudokuSession = new SudokuSession($session) ;
        $sudokuSession->setGrid($grid) ;
        $this->assertEquals($grid, $sudokuSession->getGrid()) ;
    }

    public function testSetGridCallsSessionSet()
    {
        $grid = $this->getMockBuilder('AppBundle\Entity\Grid')
                     ->getMock() ;
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                     ->getMock() ;
        $session->expects($this->once())
                ->method('set')
                ->with($this->equalTo('grid'), $grid) ;
        $sudokuSession = new SudokuSession($session) ;
        $sudokuSession->setGrid($grid) ;
    }
    
    public function testGetValuesCallsSessionGet()
    {
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                     ->getMock() ;
        $session->expects($this->once())
                ->method('get') 
                ->with($this->equalTo('values'));
        $sudokuSession = new SudokuSession($session) ;
        $sudokuSession->getValues() ;
    }
        
    public function testGetValuesReturnsValues()
    {
        $values = $this->getMockBuilder('AppBundle\Entity\Values')
                     ->getMock() ;
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                     ->getMock() ;
        $session->expects($this->once())
                ->method('get')
                ->will($this->returnValue($values));
        $sudokuSession = new SudokuSession($session) ;
        $sudokuSession->setValues($values) ;
        $this->assertEquals($values, $sudokuSession->getValues()) ;
    }
        
    public function testSetValuesCallsSessionSet()
    {
        $values = $this->getMockBuilder('AppBundle\Entity\Values')
                     ->getMock() ;
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                     ->getMock() ;
        $session->expects($this->once())
                ->method('set') 
                ->with($this->equalTo('values'), $values);
        $sudokuSession = new SudokuSession($session) ;
        $sudokuSession->setValues($values) ;
    }
    
    public function testGetTilesCallsSessionGet()
    {
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                     ->getMock() ;
        $session->expects($this->once())
                ->method('get') 
                ->with($this->equalTo('tiles'));
        $sudokuSession = new SudokuSession($session) ;
        $sudokuSession->getTiles() ;
    }

    public function testGetTilesReturnsTiles()
    {
        $tiles = $this->getMockBuilder('AppBundle\Entity\Tiles')
                     ->disableOriginalConstructor()
                     ->getMock() ;
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                     ->getMock() ;
        $session->expects($this->once())
                ->method('get')
                ->will($this->returnValue($tiles));
        $sudokuSession = new SudokuSession($session) ;
        $sudokuSession->setTiles($tiles) ;
        $this->assertEquals($tiles, $sudokuSession->getTiles()) ;
    }
        
    public function testSetTilesCallsSessionSet()
    {
        $tiles = $this->getMockBuilder('AppBundle\Entity\Tiles')
                     ->disableOriginalConstructor()
                     ->getMock() ;
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                     ->getMock() ;
        $session->expects($this->once())
                ->method('set') 
                ->with($this->equalTo('tiles'), $tiles);
        $sudokuSession = new SudokuSession($session) ;
        $sudokuSession->setTiles($tiles) ;
    }
}
