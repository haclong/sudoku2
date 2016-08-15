<?php

namespace Tests\AppBundle\Service;

use AppBundle\Service\SudokuSessionService;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Description of SudokuSessionServiceTest
 *
 * @author haclong
 */
class SudokuSessionServiceTest extends \PHPUnit_Framework_TestCase 
{
    protected function setUp() {
        $this->sudokuBag = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag')
                          ->setConstructorArgs(array('sudoku'))
                          ->getMock() ;
        
//        $mockSessionStorage = new MockArraySessionStorage() ;

        $this->session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
//                              ->setConstructorArgs(array($mockSessionStorage))
                              ->getMock() ;

        $this->session->method('registerBag') ;
//                      ->willReturn($this->sudokuBag) ;
        $this->session->method('getBag')
                      ->willReturn($this->sudokuBag) ;
        
        $this->service = new SudokuSessionService($this->session) ;
    }
    
    protected function tearDown() {
        $this->session = null ;
        $this->service = null ;
    }
    
    public function testGetSession()
    {
        $this->assertEquals($this->sudokuBag, $this->service->getSession()) ;
    }
    
    public function testSaveGrid()
    {
        $this->sudokuBag->expects($this->once())
                        ->method('set')
                        ->with($this->equalTo('grid'));
        
        $this->service->saveGrid('coucou') ;
    }
    
    public function testGetGrid()
    {
        $this->sudokuBag->expects($this->once())
                        ->method('get')
                        ->with($this->equalTo('grid'))
                        ->will($this->returnValue('coucou'));
        $this->assertEquals('coucou', $this->service->getGrid()) ;
    }
}
