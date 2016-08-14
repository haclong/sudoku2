<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Tile;
use AppBundle\Exception\AlreadyDiscardedException;
use AppBundle\Exception\ImpossibleToDiscardException;
use AppBundle\Exception\InvalidFigureException;

/**
 * Description of TileTest
 *
 * @author haclong
 */
class TileTest  extends \PHPUnit_Framework_TestCase 
{
    protected $dispatcher ;
    protected $lastPossibilityEvent ;
    protected $tileSetEvent ;
    protected $tile ;
    
    protected function setUp() {
        $this->dispatcher = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcher')
                                 ->getMock() ;

        $tileLastPossibility = $this->getMockBuilder('AppBundle\Entity\Event\TileLastPossibility')
                                    ->getMock() ;
        $this->lastPossibilityEvent = $this->getMockBuilder('AppBundle\Event\LastPossibilityEvent')
                                               ->setConstructorArgs(array($tileLastPossibility))
                                               ->getMock() ;
        $this->lastPossibilityEvent->method('getTile')
                                   ->willReturn($tileLastPossibility) ;

        $tileset = $this->getMockBuilder('AppBundle\Entity\Event\TileSet')
                        ->getMock() ;
        $this->tileSetEvent = $this->getMockBuilder('AppBundle\Event\TileSetEvent')
                                   ->setConstructorArgs(array($tileset))
                                   ->getMock() ;
        $this->tileSetEvent->method('getTile')
                           ->willReturn($tileset) ;
        
        $this->tile = new Tile($this->dispatcher, $this->tileSetEvent, $this->lastPossibilityEvent) ;
    }
    
    protected function tearDown() {
        $this->dispatcher = null ;
        $this->tileSetEvent = null ;
        $this->tileLastPossibilityEvent = null ;
        $this->tile = null ;
    }
    
    public function testPropertiesAreInitialized() {
        $this->tile->initialize(3, 4, 4) ;
        $this->assertEquals($this->tile->getRow(), 3) ;
        $this->assertEquals($this->tile->getCol(), 4) ;
        $this->assertEquals($this->tile->getRegion(), 4) ;
        $this->assertFalse($this->tile->isSolved()) ;
        $this->assertEquals($this->tile->getSize(), 4) ;
        $this->assertEquals($this->tile->getId(), '3.4') ;
    }
    
    public function testPossibilitiesFigureInitiated() {
        $this->tile->initialize(3, 4, 4) ;
        $figures = $this->tile->getPossibilitiesFigure() ;
        $this->assertEquals(4, count($figures)) ;
    }
    
    public function testReset() {
        $this->tile->initialize(3, 4, 4) ;
        $this->tile->set(3) ;
        $this->assertTrue($this->tile->isSolved()) ;
        $this->tile->reset() ;
        $this->assertFalse($this->tile->isSolved()) ;
    }
    
    public function testGetDefinitiveFigure()
    {
        $this->tile->initialize(3, 4, 4) ;
        $this->assertFalse($this->tile->getDefinitiveFigure()) ;
        $this->tile->set(3) ;
        $this->assertEquals($this->tile->getDefinitiveFigure(), 3) ;
    }
    
    public function testDiscardImpossibleToDiscardException()
    {
        $this->setExpectedException(ImpossibleToDiscardException::class) ;
        $this->tile->initialize(3, 4, 4) ;
        $this->tile->set(3) ;
        $this->tile->discard(3) ;
    }
    
    public function testDiscardInvalidFigureException()
    {
        $this->setExpectedException(InvalidFigureException::class) ;
        $this->tile->initialize(3, 4, 4) ;
        $this->tile->discard(4) ;
    }
    
    public function testDiscard()
    {
        $this->tile->initialize(3, 4, 4) ;
        $this->assertEquals(count($this->tile->getPossibilitiesFigure()), 4) ;
        $this->assertEquals(count($this->tile->getDiscardedFigure()), 0) ;
        $this->tile->discard(2) ;
        $this->assertEquals(count($this->tile->getPossibilitiesFigure()), 3) ;
        $this->assertEquals(count($this->tile->getDiscardedFigure()), 1) ;
    }
    
    public function testOnePossibilityLast()
    {
        $this->dispatcher->expects($this->once())
                   ->method('dispatch')
                   ->with('tile.lastPossibility', $this->equalTo($this->lastPossibilityEvent));

        $this->tile->initialize(3, 4, 4) ;
        $this->tile->discard(0) ;
        $this->tile->discard(1) ;
        $this->tile->discard(3) ;
    }
    
    public function testSet()
    {
        $this->tile->initialize(3, 4, 4) ;
        $this->assertFalse($this->tile->isSolved()) ;
        $this->assertEquals(count($this->tile->getPossibilitiesFigure()), 4) ;
        $this->assertEquals(count($this->tile->getDiscardedFigure()), 0) ;
        $this->tile->set(1) ;
        $this->assertEquals(count($this->tile->getPossibilitiesFigure()), 0) ;
        $this->assertEquals(count($this->tile->getDiscardedFigure()), 3) ;
        $this->assertTrue($this->tile->isSolved()) ;
    }
    
    public function testSetAlreadyDiscardedException() {
        $this->setExpectedException(AlreadyDiscardedException::class) ;
        $this->tile->initialize(3, 4, 4) ;
        $this->tile->discard(3) ;
        $this->tile->set(3) ;
    }
    
    public function testSetEventTriggered() {
        $this->dispatcher->expects($this->once())
                   ->method('dispatch')
                   ->with('tile.set', $this->equalTo($this->tileSetEvent));
        $this->tile->initialize(3, 4, 4) ;
        $this->tile->set(3) ;
    }
//    
//    public function testSetSolved() {
//        $this->tile->initialize(3, 4, 4) ;
//        $this->assertFalse($this->tile->isSolved()) ;
//        $this->tile->setSolved(true) ;
//        $this->assertTrue($this->tile->isSolved()) ;
//    }
}
