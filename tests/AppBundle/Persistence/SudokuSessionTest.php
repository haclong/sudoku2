<?php

namespace Tests\AppBundle\Persistence;

use AppBundle\Persistence\SudokuSession;


/**
 * Description of SudokuSessionTest
 *
 * @author haclong
 */
class SudokuSessionTest extends \PHPUnit_Framework_TestCase {
    protected $session ;
    protected $content ;
    protected function setUp() 
    {
        $this->session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                        ->getMock() ;
        $this->content = $this->getMockBuilder('AppBundle\Entity\Persistence\SessionContent')
                        ->getMock() ;
    }
    /**
     * @runInSeparateProcess
     */
    public function testClear()
    {
        $this->session->expects($this->once())->method('clear') ;
        $sudokuSession = new SudokuSession($this->session, $this->content) ;
        $sudokuSession->clear() ;
    }
    
    public function testReadyReturnFalseWhenSecondComponentFails()
    {
        $obj1 = $this->getMockBuilder('stdClass')->setMethods(['isReady'])->getMock() ;
        $obj1->method('isReady')->willReturn(true) ;

        $obj2 = $this->getMockBuilder('stdClass')->setMethods(['isReady'])->getMock() ;
        $obj2->method('isReady')->willReturn(false) ;

        $iterator = $this->getMockBuilder('arrayIterator')->getMock() ;
        $this->content->method('getIterator')->willReturn($iterator) ;
        
        $iterator->expects($this->at(0))->method('rewind') ;
        
        $iterator->expects($this->at(1))->method('valid')->will($this->returnValue(TRUE)) ;
        $iterator->expects($this->at(2))->method('current')->willReturn($obj1) ;
        $iterator->expects($this->at(3))->method('next') ;

        $iterator->expects($this->at(4))->method('valid')->will($this->returnValue(TRUE)) ;
        $iterator->expects($this->at(5))->method('current')->willReturn($obj2) ;
//        $iterator->expects($this->at(6))->method('next') ;
//        
//        $iterator->expects($this->at(7))->method('valid')->will($this->returnValue(FALSE)) ;
        
        $sudokuSession = new SudokuSession($this->session, $this->content) ;
        $this->assertFalse($sudokuSession->isReady()) ;
    }
    
    public function testReadyReturnFalseWhenFirstComponentFails()
    {
        $obj1 = $this->getMockBuilder('stdClass')->setMethods(['isReady'])->getMock() ;
        $obj1->method('isReady')->willReturn(false) ;

        $obj2 = $this->getMockBuilder('stdClass')->setMethods(['isReady'])->getMock() ;
        $obj2->method('isReady')->willReturn(false) ;

        $iterator = $this->getMockBuilder('arrayIterator')->getMock() ;
        $this->content->method('getIterator')->willReturn($iterator) ;
        
        $iterator->expects($this->at(0))->method('rewind') ;
        
        $iterator->expects($this->at(1))->method('valid')->will($this->returnValue(TRUE)) ;
        $iterator->expects($this->at(2))->method('current')->willReturn($obj1) ;
        
        $sudokuSession = new SudokuSession($this->session, $this->content) ;
        $this->assertFalse($sudokuSession->isReady()) ;
    }
    
    public function testReadyReturnTrueWhenAllComponentsAreTrue()
    {
        $obj1 = $this->getMockBuilder('stdClass')->setMethods(['isReady'])->getMock() ;
        $obj1->method('isReady')->willReturn(true) ;

        $obj2 = $this->getMockBuilder('stdClass')->setMethods(['isReady'])->getMock() ;
        $obj2->method('isReady')->willReturn(true) ;

        $iterator = $this->getMockBuilder('arrayIterator')->getMock() ;
        $this->content->method('getIterator')->willReturn($iterator) ;
        
        $iterator->expects($this->at(0))->method('rewind') ;
        
        $iterator->expects($this->at(1))->method('valid')->will($this->returnValue(TRUE)) ;
        $iterator->expects($this->at(2))->method('current')->willReturn($obj1) ;
        $iterator->expects($this->at(3))->method('next') ;

        $iterator->expects($this->at(4))->method('valid')->will($this->returnValue(TRUE)) ;
        $iterator->expects($this->at(5))->method('current')->willReturn($obj2) ;
        $iterator->expects($this->at(6))->method('next') ;
        
        $iterator->expects($this->at(7))->method('valid')->will($this->returnValue(FALSE)) ;
        
        $sudokuSession = new SudokuSession($this->session, $this->content) ;
        $this->assertTrue($sudokuSession->isReady()) ;
    }
    /**
     * @runInSeparateProcess
     */
    public function testGetGridReturnsGrid()
    {
        $this->session->method('has')->willReturn(true) ;
        $this->session->expects($this->once())->method('get')->with('grid') ;
        $sudokuSession = new SudokuSession($this->session, $this->content) ;
        $sudokuSession->getGrid() ;
    }
    /**
     * @runInSeparateProcess
     */
    public function testGetGridReturnsNull()
    {
        $this->session->method('has')->willReturn(false) ;
        $sudokuSession = new SudokuSession($this->session, $this->content) ;
        $this->assertNull($sudokuSession->getGrid()) ;
    }
    /**
     * @runInSeparateProcess
     */
    public function testGetValuesReturnsValues()
    {
        $this->session->method('has')->willReturn(true) ;
        $this->session->expects($this->once())->method('get')->with('values') ;
        $sudokuSession = new SudokuSession($this->session, $this->content) ;
        $sudokuSession->getValues() ;
    }
    /**
     * @runInSeparateProcess
     */
    public function testGetValuesReturnsNull()
    {
        $this->session->method('has')->willReturn(false) ;
        $sudokuSession = new SudokuSession($this->session, $this->content) ;
        $this->assertNull($sudokuSession->getValues()) ;
    }
    /**
     * @runInSeparateProcess
     */
    public function testGetTilesReturnsTiles()
    {
        $this->session->method('has')->willReturn(true) ;
        $this->session->expects($this->once())->method('get')->with('tiles') ;
        $sudokuSession = new SudokuSession($this->session, $this->content) ;
        $sudokuSession->getTiles() ;
    }
    /**
     * @runInSeparateProcess
     */
    public function testGetTilesReturnsNull()
    {
        $this->session->method('has')->willReturn(false) ;
        $sudokuSession = new SudokuSession($this->session, $this->content) ;
        $this->assertNull($sudokuSession->getTiles()) ;
    }
}
