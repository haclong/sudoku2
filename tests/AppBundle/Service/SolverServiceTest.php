<?php

namespace Tests\AppBundle\Service;
use AppBundle\Service\SolverService;

/**
 * Description of SolverServiceTest
 *
 * @author haclong
 */
class SolverServiceTest extends \PHPUnit_Framework_TestCase {
    protected $dispatcher ;
    protected $session ;
    protected $event ;
    protected $tiles ;
    
    protected function setUp()
    {
        $this->tiles = $this->getMockBuilder('AppBundle\Entity\Tiles')->disableOriginalConstructor()->getMock() ;
        
        $this->dispatcher = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcher')
                                 ->getMock() ;
        
        $this->event = $this->getMockBuilder('AppBundle\Event\SetTileEvent')
                            ->disableOriginalConstructor()
                            ->getMock() ;
        
        $this->session = $this->getMockBuilder('AppBundle\Persistence\TilesSession')
                             ->disableOriginalConstructor()
                             ->getMock() ;
        $this->session->method('getTiles')->willReturn($this->tiles) ;
    }
    
    protected function tearDown() {
        $this->dispatcher = null ;
        $this->event = null ;
        $this->session = null ;
        $this->tiles = null ;
    }
    
    public function testTrue()
    {
        $this->assertTrue(true) ;
    }
    
    public function testNoNextMoveAvailable()
    {

        $this->tiles->method('getIndexToSet')->willReturn(null) ;
        
        $this->dispatcher->expects($this->never())->method('dispatch') ;
        $service = new SolverService($this->dispatcher, $this->event, $this->session) ;
        $service->nextMove() ;
    }
    
    public function testNextMoveAvailable()
    {
        $tileset = $this->getMockBuilder('stdClass')->setMethods(['set'])->getMock() ;

        $this->tiles->method('getIndexToSet')->willReturn('3') ;
        $this->tiles->method('getFirstTileToSolve')->willReturn('5.5') ;
        $this->event->method('getTile')->willReturn($tileset) ;
        
        $tileset->expects($this->once())->method('set')->with(5, 5, 3) ;
        $this->tiles->expects($this->exactly(2))->method('getFirstTileToSolve') ;
        $this->dispatcher->expects($this->once())->method('dispatch') ;
        
        $service = new SolverService($this->dispatcher, $this->event, $this->session) ;
        $service->nextMove() ;
    }
}
