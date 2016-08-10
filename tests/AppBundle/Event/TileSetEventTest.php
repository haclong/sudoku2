<?php

namespace Tests\AppBundle\Event;

use AppBundle\Event\TileSetEvent;

/**
 * Description of TileSetEventTest
 *
 * @author haclong
 */
class TileSetEventTest extends \PHPUnit_Framework_TestCase  {
    public function testConstructor()
    {
        $tile = $this->getMockBuilder('AppBundle\Entity\Event\TileSet')
                     ->getMock() ;
        $event = new TileSetEvent($tile) ;
        $this->assertInstanceOf('AppBundle\Event\TileSetEvent', $event) ;
        $this->assertInstanceOf('AppBundle\Entity\Event\TileSet', $event->getTile()) ;
        $this->assertEquals($event::NAME, 'tile.set') ;
    }
}
