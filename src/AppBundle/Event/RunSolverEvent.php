<?php

namespace AppBundle\Event;

use AppBundle\Persistence\GridSession;
use Symfony\Component\EventDispatcher\Event;
/**
 * Description of RunSolverEvent
 *
 * @author haclong
 */
class RunSolverEvent extends Event {
    protected $gridSession ;
    const NAME = 'solver.run' ;
    public function set(GridSession $session)
    {
        $this->gridSession = $session ;
    }
    
    public function getGridSession()
    {
        return $this->gridSession ;
    }
}
