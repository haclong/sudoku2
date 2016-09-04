<?php

namespace Tests\AppBundle\Event;

use AppBundle\Event\GetGridEvent;

/**
 * Description of GetGridEventTest
 *
 * @author haclong
 */
class GetGridEventTest extends \PHPUnit_Framework_TestCase  {
    public function testConstructor()
    {
        $tiles = $this->getMockBuilder('AppBundle\Entity\Event\TilesLoaded')
                     ->disableOriginalConstructor()
                     ->getMock() ;
        $event = new GetGridEvent($tiles) ;
        $this->assertInstanceOf('AppBundle\Entity\Event\TilesLoaded', $event->getTiles()) ;
        $this->assertEquals($event::NAME, 'grid.get') ;
    }
}
