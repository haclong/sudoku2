<?php

namespace Tests\AppBundle\Event;

use AppBundle\Event\SetTileEvent;

/**
 * Description of SetTileEventTest
 *
 * @author haclong
 */
class SetTileEventTest extends \PHPUnit_Framework_TestCase  {
    public function testConstructor()
    {
        $tile = $this->getMockBuilder('AppBundle\Entity\Event\TileSet')
                     ->getMock() ;
        $event = new SetTileEvent($tile) ;
        $this->assertInstanceOf('AppBundle\Entity\Event\TileSet', $event->getTile()) ;
        $this->assertEquals($event::NAME, 'tile.set') ;
    }
}
