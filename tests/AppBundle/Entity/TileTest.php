<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Tile;
use AppBundle\Exception\AlreadyDiscardedException;
use AppBundle\Exception\ImpossibleToDiscardException;

/**
 * Description of TileTest
 *
 * @author haclong
 */
class TileTest extends \PHPUnit_Framework_TestCase {
    public function testInitialize()
    {
        $tile = new Tile() ;
        $tile->initialize(3, 4, 4) ;
        $this->assertEquals($tile->getRow(), 3) ;
        $this->assertEquals($tile->getCol(), 4) ;
        $this->assertEquals($tile->getRegion(), 4) ;
        $this->assertFalse($tile->isSolved()) ;
        $this->assertEquals($tile->getSize(), 4) ;
        $this->assertEquals($tile->getId(), '3.4') ;
        $this->assertNull($tile->getValue()) ;
    }
    
    public function testDiscard()
    {
        $tile = new Tile() ;
        $tile->initialize(3, 4, 4) ;
        $this->assertEquals(4, count($tile->getMaybeValues())) ;
        $this->assertEquals(0, count($tile->getDiscardValues())) ;
        $tile->discard(2) ;
        $this->assertEquals(3, count($tile->getMaybeValues())) ;
        $this->assertEquals(1, count($tile->getDiscardValues())) ;
        $this->assertFalse($tile->isSolved()) ;
        $this->assertNull($tile->getValue()) ;
    }
    
    public function testDiscardImpossibleToDiscardException()
    {
        $this->setExpectedException(ImpossibleToDiscardException::class) ;
        $tile = new Tile() ;
        $tile->initialize(3, 4, 4) ;
        $tile->set(3) ;
        $tile->discard(3) ;
    }
    
    public function testSet()
    {
        $tile = new Tile() ;
        $tile->initialize(3, 4, 4) ;
        $this->assertNull($tile->getValue()) ;
        $tile->set(1) ;
        $this->assertEquals(0, count($tile->getMaybeValues())) ;
        $this->assertEquals(3, count($tile->getDiscardValues())) ;
        $this->assertEquals(1, $tile->getValue()) ;
        $this->assertTrue($tile->isSolved()) ;
    }
    
    public function testSetAlreadyDiscardedException() {
        $this->setExpectedException(AlreadyDiscardedException::class) ;
        $tile = new Tile() ;
        $tile->initialize(3, 4, 4) ;
        $tile->discard(3) ;
        $tile->set(3) ;
    }
    
    public function testReset()
    {
        $tile = new Tile() ;
        $tile->initialize(3, 4, 4) ;
        $tile->set(1) ;
        $tile->reset() ;
        $this->assertEquals($tile->getRow(), 3) ;
        $this->assertEquals($tile->getCol(), 4) ;
        $this->assertEquals($tile->getRegion(), 4) ;
        $this->assertFalse($tile->isSolved()) ;
        $this->assertEquals($tile->getSize(), 4) ;
        $this->assertEquals($tile->getId(), '3.4') ;
        $this->assertNull($tile->getValue()) ;
    }
}
