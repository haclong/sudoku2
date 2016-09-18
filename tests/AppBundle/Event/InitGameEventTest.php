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
        $grid = $this->getMockBuilder('AppBundle\Entity\Grid')
                     ->disableOriginalConstructor()
                     ->getMock() ;
        $values = $this->getMockBuilder('AppBundle\Entity\Values')
                     ->disableOriginalConstructor()
                     ->getMock() ;
        $tiles = $this->getMockBuilder('AppBundle\Entity\Tiles')
                     ->disableOriginalConstructor()
                     ->getMock() ;

        $event = new InitGameEvent($grid, $values, $tiles) ;
        $this->assertEquals($event::NAME, 'game.init') ;
        $this->assertSame($grid, $event->getGrid()) ;
        $this->assertSame($values, $event->getValues()) ;
        $this->assertSame($tiles, $event->getTiles()) ;
    }
}
