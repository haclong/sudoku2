<?php

namespace Tests\AppBundle\Entity\Event;

use AppBundle\Entity\Event\GridSize;

/**
 * Description of GridSizeTest
 *
 * @author haclong
 */
class GridSizeTest  extends \PHPUnit_Framework_TestCase {
    public function testConstructor() {
        $tile = new GridSize(9) ;
        $this->assertEquals($tile->get(), 9) ;
    }}
