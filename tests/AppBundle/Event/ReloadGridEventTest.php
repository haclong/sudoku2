<?php

namespace Tests\AppBundle\Event;

use AppBundle\Event\ReloadGridEvent;

/**
 * Description of ReloadGridEventTest
 *
 * @author haclong
 */
class ReloadGridEventTest extends \PHPUnit_Framework_TestCase  {
    public function testConstructor()
    {
        $event = new ReloadGridEvent() ;
        $this->assertEquals($event::NAME, 'grid.reload') ;
    }
}
