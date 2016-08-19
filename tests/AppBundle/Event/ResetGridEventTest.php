<?php

namespace Tests\AppBundle\Event;

use AppBundle\Event\ResetGridEvent;

/**
 * Description of ResetGridEventTest
 *
 * @author haclong
 */
class ResetGridEventTest extends \PHPUnit_Framework_TestCase  {
    public function testConstructor()
    {
        $event = new ResetGridEvent() ;
        $this->assertEquals($event::NAME, 'grid.reset') ;
    }
}
