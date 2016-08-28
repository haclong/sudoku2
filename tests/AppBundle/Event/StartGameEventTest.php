<?php

namespace Tests\AppBundle\Event;

use AppBundle\Event\StartGameEvent;

/**
 * Description of StartGameEventTest
 *
 * @author haclong
 */
class StartGameEventTest extends \PHPUnit_Framework_TestCase  {
    public function testConstructor()
    {
        $size = $this->getMockBuilder('AppBundle\Entity\Event\GridSize')
                     ->disableOriginalConstructor()
                     ->getMock() ;

        $event = new StartGameEvent($size) ;
        $this->assertEquals($event::NAME, 'game.start') ;
        $this->assertInstanceOf('AppBundle\Entity\Event\GridSize', $event->getGridSize()) ;
    }
}
