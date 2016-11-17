<?php

namespace Tests\AppBundle\Event;

use AppBundle\Event\RunSolverEvent;

/**
 * Description of RunSolverEventTest
 *
 * @author haclong
 */
class RunSolverEventTest extends \PHPUnit_Framework_TestCase  {
    public function testConstructor()
    {
        $grid = $this->getMockBuilder('AppBundle\Persistence\GridSession')->disableOriginalConstructor()->getMock() ;
        $event = new RunSolverEvent() ;
        $event->set($grid) ;
        $this->assertEquals($event::NAME, 'solver.run') ;
        $this->assertInstanceOf('AppBundle\Persistence\GridSession', $event->getGridSession()) ;
    }
}
