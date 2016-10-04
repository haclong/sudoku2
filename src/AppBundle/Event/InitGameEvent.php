<?php

namespace AppBundle\Event;

use AppBundle\Entity\Event\SudokuEntities;
use Symfony\Component\EventDispatcher\Event;

/**
 * Description of InitGameEvent
 *
 * @author haclong
 */
class InitGameEvent extends Event {
    const NAME = 'game.init' ;
    protected $entities ;
    
    public function __construct(SudokuEntities $entities)
    {
        $this->entities = $entities ;
    }
    
    public function getEntity($key) {
        return $this->entities->offsetGet($key) ;
    }
}
