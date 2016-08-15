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
        $grid = $this->getMockBuilder('AppBundle\Entity\Grid')
                     ->disableOriginalConstructor()
                     ->getMock() ;
        $event = new GetGridEvent($grid) ;
        $this->assertInstanceOf('AppBundle\Entity\Grid', $event->getGrid()) ;
        $this->assertEquals($event::NAME, 'grid.get') ;
    }
}
