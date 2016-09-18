<?php

namespace Tests\AppBundle\Utils;

use AppBundle\Utils\SudokuSession;

/**
 * Description of SudokuSessionTest
 *
 * @author haclong
 */
class SudokuSessionTest extends \PHPUnit_Framework_TestCase {
    public function testClear()
    {
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                     ->getMock() ;
        $session->expects($this->once())
                ->method('clear') ;
        $sudokuSession = new SudokuSession($session) ;
        $sudokuSession->clear() ;
    }
    
    public function testGetGridCallsSessionGet()
    {
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                     ->getMock() ;
        $session->expects($this->once())
                ->method('get')
                ->with($this->equalTo('grid')) ;
        $session->method('has')
                ->with('grid')
                ->willReturn(true) ;
        $sudokuSession = new SudokuSession($session) ;
        $sudokuSession->getGrid() ;
    }
        
    public function testGetGridReturnsGrid()
    {
        $grid = $this->getMockBuilder('AppBundle\Entity\Grid')
                     ->getMock() ;
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                     ->getMock() ;
        $session->method('get') 
                ->with('grid')
                ->willReturn($grid) ;
        $session->method('has')
                ->with('grid')
                ->willReturn(true) ;
        $sudokuSession = new SudokuSession($session) ;
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
        $this->assertThat($sudokuSession, $this->logicalNot($this->attributeEqualTo('grid', $grid))) ;
        $sudokuSession->setGrid($grid) ;
        $this->assertThat($sudokuSession, $this->attributeEqualTo('grid', $grid)) ;
    }
    
    public function testGetValuesCallsSessionGet()
    {
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                     ->getMock() ;
        $session->expects($this->once())
                ->method('get') 
                ->with($this->equalTo('values'));
        $session->method('has')
                ->with('values')
                ->willReturn(true) ;
        $sudokuSession = new SudokuSession($session) ;
        $sudokuSession->getValues() ;
    }

    public function testGetValuesReturnsValues()
    {
        $values = $this->getMockBuilder('AppBundle\Entity\Values')
                     ->getMock() ;
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                     ->getMock() ;
        $session->method('get')
                ->with('values')
                ->willReturn($values) ;
        $session->method('has')
                ->with('values')
                ->willReturn(true) ;
        $sudokuSession = new SudokuSession($session) ;
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
                ->with($this->equalTo('values'));
        $sudokuSession = new SudokuSession($session) ;
        $this->assertThat($sudokuSession, $this->logicalNot($this->attributeEqualTo('values', $values))) ;
        $sudokuSession->setValues($values) ;
        $this->assertThat($sudokuSession, $this->attributeEqualTo('values', $values)) ;
    }
    
    public function testGetTilesCallsSessionGet()
    {
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                     ->getMock() ;
        $session->expects($this->once())
                ->method('get') 
                ->with($this->equalTo('tiles'));
        $session->method('has')
                ->with('tiles')
                ->willReturn(true) ;
        $sudokuSession = new SudokuSession($session) ;
        $sudokuSession->getTiles() ;
    }

    public function testGetTilesReturnsValues()
    {
        $tiles = $this->getMockBuilder('AppBundle\Entity\Tiles')
                     ->disableOriginalConstructor()
                     ->getMock() ;
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                     ->getMock() ;
        $session->method('get')
                ->with('tiles')
                ->willReturn($tiles) ;
        $session->method('has')
                ->with('tiles')
                ->willReturn(true) ;
        $sudokuSession = new SudokuSession($session) ;
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
                ->with($this->equalTo('tiles'));
        $sudokuSession = new SudokuSession($session) ;
        $this->assertThat($sudokuSession, $this->logicalNot($this->attributeEqualTo('tiles', $tiles))) ;
        $sudokuSession->setTiles($tiles) ;
        $this->assertThat($sudokuSession, $this->attributeEqualTo('tiles', $tiles)) ;
    }
}
