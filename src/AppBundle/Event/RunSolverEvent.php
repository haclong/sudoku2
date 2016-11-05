<?php

namespace AppBundle\Event;
use Symfony\Component\EventDispatcher\Event;
/**
 * Description of RunSolverEvent
 *
 * @author haclong
 */
class RunSolverEvent extends Event {
    const NAME = 'solver.run' ;
}
