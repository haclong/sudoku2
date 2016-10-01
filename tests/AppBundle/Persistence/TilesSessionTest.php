<?php


namespace Tests\AppBundle\Persistence;
use AppBundle\Persistence\TilesSession;

/**
 * Description of TilesSessionTest
 *
 * @author haclong
 */
class TilesSessionTest extends \PHPUnit_Framework_TestCase {
    protected $session ;
    protected function setUp() 
    {
        $this->session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                        ->getMock() ;
    }
    
    public function testGetTilesCallsSessionGet()
    {
        $this->session->expects($this->once())
                ->method('get') 
                ->with($this->equalTo('tiles'));
        $tilesSession = new TilesSession($this->session) ;
        $tilesSession->getTiles() ;
    }

    public function testGetTilesReturnsTiles()
    {
        $tiles = $this->getMockBuilder('AppBundle\Entity\Tiles')
                     ->disableOriginalConstructor()
                     ->getMock() ;
        $this->session->expects($this->once())
                ->method('get')
                ->will($this->returnValue($tiles));
        $tilesSession = new TilesSession($this->session) ;
        $tilesSession->setTiles($tiles) ;
        $this->assertEquals($tiles, $tilesSession->getTiles()) ;
    }
        
    public function testSetTilesCallsSessionSet()
    {
        $tiles = $this->getMockBuilder('AppBundle\Entity\Tiles')
                     ->disableOriginalConstructor()
                     ->getMock() ;
        $this->session->expects($this->once())
                ->method('set') 
                ->with($this->equalTo('tiles'), $tiles);
        $tilesSession = new TilesSession($this->session) ;
        $tilesSession->setTiles($tiles) ;
    }
    
    public function testTilesStoredReturnTrue()
    {
        $tiles = $this->getMockBuilder('AppBundle\Entity\Tiles')
                     ->disableOriginalConstructor()
                     ->getMock() ;
        $this->session->method('get')
                      ->with('tiles')
                      ->willReturn($tiles) ;
        $tilesSession = new TilesSession($this->session) ;
        $this->assertTrue($tilesSession->isReady()) ;
    }
    
    public function testTilesNotStoredReturnFalse()
    {
        $this->session->method('get')
                      ->with('tiles')
                      ->willReturn(null) ;
        $tilesSession = new TilesSession($this->session) ;
        $this->assertFalse($tilesSession->isReady()) ;
    }
}
