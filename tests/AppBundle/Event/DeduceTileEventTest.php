<?php

namespace Tests\AppBundle\Event;

use AppBundle\Event\DeduceTileEvent;

/**
 * Description of DeduceTileEventTest
 *
 * @author haclong
 */
class DeduceTileEventTest extends \PHPUnit_Framework_TestCase  {
    public function testConstructor()
    {
        $tile = $this->getMockBuilder('AppBundle\Entity\Event\TileLastPossibility')
                     ->getMock() ;
        $event = new DeduceTileEvent($tile) ;
        $this->assertInstanceOf('AppBundle\Entity\Event\TileLastPossibility', $event->getTile()) ;
        $this->assertEquals($event::NAME, 'tile.deduce') ;
    }
}
