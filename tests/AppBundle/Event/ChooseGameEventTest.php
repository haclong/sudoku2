<?php

namespace Tests\AppBundle\Event;

use AppBundle\Event\ChooseGameEvent;

/**
 * Description of ChooseGameEventTest
 *
 * @author haclong
 */
class ChooseGameEventTest extends \PHPUnit_Framework_TestCase  {
    public function testConstructor()
    {
        $size = $this->getMockBuilder('AppBundle\Entity\Event\GridSize')
                     ->disableOriginalConstructor()
                     ->getMock() ;

        $event = new ChooseGameEvent($size) ;
        $this->assertEquals($event::NAME, 'game.choose') ;
        $this->assertInstanceOf('AppBundle\Entity\Event\GridSize', $event->getGridSize()) ;
    }
}
