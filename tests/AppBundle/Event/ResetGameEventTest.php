<?php

namespace Tests\AppBundle\Event;
use AppBundle\Event\ResetGameEvent;

/**
 * Description of ResetGameEventTest
 *
 * @author haclong
 */
class ResetGameEventTest extends \PHPUnit_Framework_TestCase  {
    public function testConstructor()
    {
        $event = new ResetGameEvent() ;
        $this->assertEquals($event::NAME, 'game.reset') ;
    }
}
