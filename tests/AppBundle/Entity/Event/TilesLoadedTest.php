<?php

namespace Tests\AppBundle\Entity\Event;

use AppBundle\Entity\Event\TilesLoaded;

/**
 * Description of TilesLoadedTest
 *
 * @author haclong
 */
class TilesLoadedTest extends \PHPUnit_Framework_TestCase {
    public function testConstructor() {
        $tiles = array() ;
        $size = 3 ;
        $tile = new TilesLoaded($size, $tiles) ;
        $this->assertEquals($size, $tile->getSize()) ;
        $this->assertEquals($tiles, $tile->getTiles()) ;
    }
}
