<?php

namespace Tests\AppBundle\Event;

use AppBundle\Event\LoadGameEvent;

/**
 * Description of LoadGameEventTest
 *
 * @author haclong
 */
class LoadGameEventTest extends \PHPUnit_Framework_TestCase  {
    public function testConstructor()
    {
        $tiles = $this->getMockBuilder('AppBundle\Entity\Event\TilesLoaded')
                     ->disableOriginalConstructor()
                     ->getMock() ;
        $event = new LoadGameEvent($tiles) ;
        $this->assertInstanceOf('AppBundle\Entity\Event\TilesLoaded', $event->getTiles()) ;
        $this->assertEquals($event::NAME, 'game.load') ;
    }
}
