<?php

namespace Tests\AppBundle\Event;
use AppBundle\Event\ClearGridEvent;

/**
 * Description of ClearGridEventTest
 *
 * @author haclong
 */
class ClearGridEventTest extends \PHPUnit_Framework_TestCase  {
    public function testConstructor()
    {
        $event = new ClearGridEvent() ;
        $this->assertEquals($event::NAME, 'grid.clear') ;
    }
}
