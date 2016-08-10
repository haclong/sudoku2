<?php

namespace Tests\AppBundle\Event;

use AppBundle\Event\LastPossibilityEvent;

/**
 * Description of LastPossibilityEventTest
 *
 * @author haclong
 */
class LastPossibilityEventTest extends \PHPUnit_Framework_TestCase  {
    public function testConstructor()
    {
        $tile = $this->getMockBuilder('AppBundle\Entity\Event\TileLastPossibility')
                     ->getMock() ;
        $event = new LastPossibilityEvent($tile) ;
        $this->assertInstanceOf('AppBundle\Event\LastPossibilityEvent', $event) ;
        $this->assertInstanceOf('AppBundle\Entity\Event\TileLastPossibility', $event->getTile()) ;
        $this->assertEquals($event::NAME, 'tile.lastPossibility') ;
    }
}
