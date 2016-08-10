<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Event\TileLastPossibility;

/**
 * Description of TileLastPossibilityTest
 *
 * @author haclong
 */
class TileLastPossibilityTest  extends \PHPUnit_Framework_TestCase {
    public function testConstructor() {
        $tile = new TileLastPossibility() ;
        $tile->set(3, 4, 2, 5) ;
        $this->assertEquals($tile->getRow(), 3) ;
        $this->assertEquals($tile->getCol(), 4) ;
        $this->assertEquals($tile->getRegion(), 2) ;
        $this->assertEquals($tile->getFigure(), 5) ;
    }
}
