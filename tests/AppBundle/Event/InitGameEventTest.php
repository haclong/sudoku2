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

        $event = new InitGameEvent($entities) ;
        $this->assertEquals($event::NAME, 'game.init') ;
        $this->assertSame($grid, $event->getEntity('gridentity')) ;
        $this->assertSame($values, $event->getEntity('valuesentity')) ;
        $this->assertSame($tiles, $event->getEntity('tilesentity')) ;
    }
}
