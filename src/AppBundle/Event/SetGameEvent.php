<?php

namespace AppBundle\Event;

use AppBundle\Entity\Event\SudokuEntities;
use Symfony\Component\EventDispatcher\Event;

/**
 * Description of SetGameEvent
 *
 * @author haclong
 */
class SetGameEvent extends Event {
    const NAME = 'game.set' ;
    protected $entities ;
    
    public function __construct(SudokuEntities $entities)
    {
        $this->entities = $entities ;
    }
    
    public function getEntity($key) {
        return $this->entities->offsetGet($key) ;
    }
}
