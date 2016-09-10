<?php

namespace Tests\AppBundle\Event;

use AppBundle\Event\ReloadGameEvent;

/**
 * Description of ReloadGameEventTest
 *
 * @author haclong
 */
class ReloadGameEventTest extends \PHPUnit_Framework_TestCase  {
    public function testConstructor()
    {
        $grid = $this->getMockBuilder('AppBundle\Entity\Grid')
                     ->disableOriginalConstructor()
                     ->getMock() ;
        $event = new ReloadGameEvent($grid) ;
        $this->assertEquals($event::NAME, 'game.reload') ;
        $this->assertInstanceOf('AppBundle\Entity\Grid', $event->getGrid()) ;
    }
}
