<?php

namespace Tests\AppBundle\Event;

use AppBundle\Event\ReloadGameEvent;

/**
 * Description of ReloadGameEventTest
 *
 * @author haclong
 */
class ReloadGameEventTest extends \PHPUnit_Framework_TestCase  {
    public function testConstructor()
    {
        $event = new ReloadGameEvent() ;
        $this->assertEquals($event::NAME, 'game.reload') ;
    }
}
