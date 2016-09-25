<?php

namespace Tests\AppBundle\Event;

use AppBundle\Event\ValidateTileSetEvent;

/**
 * Description of ValidateTileSetEventTest
 *
 * @author haclong
 */
class ValidateTileSetEventTest extends \PHPUnit_Framework_TestCase  {
    public function testConstructor()
    {
        $tile = $this->getMockBuilder('AppBundle\Entity\Event\TileSet')
                     ->getMock() ;
        $event = new ValidateTileSetEvent($tile) ;
        $this->assertInstanceOf('AppBundle\Entity\Event\TileSet', $event->getTile()) ;
        $this->assertEquals($event::NAME, 'settile.validated') ;
    }
}
