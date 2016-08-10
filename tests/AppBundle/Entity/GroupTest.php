<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Group;
use AppBundle\Exception\InvalidCountOfTilesInGroupException;

/**
 * Description of GroupTest
 *
 * @author haclong
 */
class GroupTest extends \PHPUnit_Framework_TestCase {
    public function testSet() {
        $group = new Group() ;
        $group->set('row', 2, 4) ;
        $this->assertEquals($group->getId(), 'row.2') ;
        $this->assertEquals($group->getType(), 'row') ;
        $this->assertEquals($group->getIndex(), 2) ;
        $this->assertFalse($group->isSolved()); 
    }
    
    public function testSolve() {
        $group = new Group() ;
        $group->set('row', 2, 4) ;
        $this->assertFalse($group->isSolved()) ;
        $group->solve(true) ;
        $this->assertTrue($group->isSolved()) ;
    }
    
    public function testInvalidCountOfTilesInGroupException() {
        $this->setExpectedException(InvalidCountOfTilesInGroupException::class) ;
        $group = new Group() ;
        $group->set('row', 2, 4) ;
        $group->addTile('0.0') ;
        $group->addTile('0.1') ;
        $group->addTile('0.2') ;
        $group->addTile('0.3') ;
        $group->addTile('0.4') ;
        $this->assertEquals(count($group->getTiles()), 4) ;
        $this->assertTrue(in_array($group->getTiles(), '0.3')) ;
        $this->assertFalse(in_array($group->getTiles(), '0.4')) ;
    }
}
