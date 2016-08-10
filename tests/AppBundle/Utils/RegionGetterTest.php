<?php

namespace Tests\AppBundle\Utils;

use AppBundle\Utils\RegionGetter ;

/**
 * Description of RegionGetterTest
 *
 * @author haclong
 */
class RegionGetterTest  extends \PHPUnit_Framework_TestCase 
{
    public function testGetRegion() {
        $this->assertEquals(RegionGetter::getRegion(1,1, 9), 0) ;
        $this->assertEquals(RegionGetter::getRegion(0,3, 9), 1) ;
        $this->assertEquals(RegionGetter::getRegion(2,6, 9), 2) ;
        $this->assertEquals(RegionGetter::getRegion(3,0, 9), 3) ;
        $this->assertEquals(RegionGetter::getRegion(4,4, 9), 4) ;
        $this->assertEquals(RegionGetter::getRegion(5,6, 9), 5) ;
        $this->assertEquals(RegionGetter::getRegion(6,2, 9), 6) ;
        $this->assertEquals(RegionGetter::getRegion(7,5, 9), 7) ;
        $this->assertEquals(RegionGetter::getRegion(8,6, 9), 8) ;
    }
}
