<?php

namespace Tests\AppBundle\Event;

use AppBundle\Event\ChooseGridEvent;

/**
 * Description of ChooseGridEventTest
 *
 * @author haclong
 */
class ChooseGridEventTest extends \PHPUnit_Framework_TestCase  {
    public function testConstructor()
    {
        $size = $this->getMockBuilder('AppBundle\Entity\Event\GridSize')
                     ->disableOriginalConstructor()
                     ->getMock() ;

        $event = new ChooseGridEvent($size) ;
        $this->assertEquals($event::NAME, 'grid.choose') ;
        $this->assertInstanceOf('AppBundle\Entity\Event\GridSize', $event->getGridSize()) ;
    }
}
