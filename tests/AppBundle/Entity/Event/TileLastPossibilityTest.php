<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Event\TileLastPossibility;

/**
 * Description of TileLastPossibilityTest
 *
 * @author haclong
 */
class TileLastPossibilityTest  extends \PHPUnit_Framework_TestCase {
    public function testInitialValuesAreNull() {
        $tile = new TileLastPossibility() ;
        $this->assertNull($tile->getRow()) ;
        $this->assertNull($tile->getCol()) ;
        $this->assertNull($tile->getValue()) ;
    }
    public function testConstructor() {
        $tile = new TileLastPossibility() ;
        $tile->set(3, 4, 5) ;
        $this->assertEquals(3, $tile->getRow()) ;
        $this->assertEquals(4, $tile->getCol()) ;
        $this->assertEquals(5, $tile->getValue()) ;
    }
}
