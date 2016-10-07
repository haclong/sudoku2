<?php

namespace Tests\AppBundle\Event;

use AppBundle\Event\InitGameEvent;

/**
 * Description of InitGameEventTest
 *
 * @author haclong
 */
class InitGameEventTest extends \PHPUnit_Framework_TestCase  {
    public function testConstructor()
    {
        $size = $this->getMockBuilder('AppBundle\Entity\Event\GridSize')
                     ->disableOriginalConstructor()
                     ->getMock() ;

        $event = new InitGameEvent($size) ;
        $this->assertEquals($event::NAME, 'game.init') ;
        $this->assertInstanceOf('AppBundle\Entity\Event\GridSize', $event->getGridSize()) ;
    }
}
