<?php

namespace Tests\AppBundle\Event;

use AppBundle\Event\SetGameEvent;

/**
 * Description of SetGameEventTest
 *
 * @author haclong
 */
class SetGameEventTest extends \PHPUnit_Framework_TestCase  {
    public function testConstructor()
    {
        $entities = $this->getMockBuilder('AppBundle\Entity\Event\SudokuEntities')
                     ->disableOriginalConstructor()
                     ->getMock() ;
        $grid = $this->getMockBuilder('AppBundle\Entity\Grid')
                     ->disableOriginalConstructor()
                     ->getMock() ;
        $values = $this->getMockBuilder('AppBundle\Entity\Values')
                     ->disableOriginalConstructor()
                     ->getMock() ;
        $tiles = $this->getMockBuilder('AppBundle\Entity\Tiles')
                     ->disableOriginalConstructor()
                     ->getMock() ;
        $entities->method('offsetGet')
                 ->will($this->onConsecutiveCalls($grid, $values, $tiles)) ;

        $event = new SetGameEvent($entities) ;
        $this->assertEquals($event::NAME, 'game.set') ;
        $this->assertSame($grid, $event->getEntity('gridentity')) ;
        $this->assertSame($values, $event->getEntity('valuesentity')) ;
        $this->assertSame($tiles, $event->getEntity('tilesentity')) ;
    }
}
