<?php

namespace Tests\AppBundle\Persistence;

use AppBundle\Persistence\GridSession;


/**
 * Description of GridSessionTest
 *
 * @author haclong
 */
class GridSessionTest extends \PHPUnit_Framework_TestCase {
    protected $session ;
    protected function setUp() 
    {
        $this->session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                        ->getMock() ;
    }

    public function testGetGridCallsSessionGet()
    {
        $this->session->expects($this->once())
                ->method('get')
                ->with($this->equalTo('grid')) ;
        $gridSession = new GridSession($this->session) ;
        $gridSession->getGrid() ;
    }
        
    public function testGetGridReturnsGrid()
    {
        $grid = $this->getMockBuilder('AppBundle\Entity\Grid')
                     ->getMock() ;
        $this->session->expects($this->once())
                ->method('get')
                ->will($this->returnValue($grid));
        $gridSession = new GridSession($this->session) ;
        $gridSession->setGrid($grid) ;
        $this->assertEquals($grid, $gridSession->getGrid()) ;
    }

    public function testSetGridCallsSessionSet()
    {
        $grid = $this->getMockBuilder('AppBundle\Entity\Grid')
                     ->getMock() ;
        $this->session->expects($this->once())
                ->method('set')
                ->with($this->equalTo('grid'), $grid) ;
        $gridSession = new GridSession($this->session) ;
        $gridSession->setGrid($grid) ;
    }
    
    public function testGridStoredReturnTrue()
    {
        $grid = $this->getMockBuilder('AppBundle\Entity\Grid')
                     ->getMock() ;
        $this->session->method('get')
                      ->with('grid')
                      ->willReturn($grid) ;
        $gridSession = new GridSession($this->session) ;
        $this->assertTrue($gridSession->isReady()) ;
    }
    
    public function testGridNotStoredReturnFalse()
    {
        $this->session->method('get')
                      ->with('grid')
                      ->willReturn(false) ;
        $gridSession = new GridSession($this->session) ;
        $this->assertFalse($gridSession->isReady()) ;
    }
}
