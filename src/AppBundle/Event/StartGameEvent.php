<?php

namespace AppBundle\Event;

use AppBundle\Entity\Event\GridSize;
use Symfony\Component\EventDispatcher\Event;

/**
 * Description of StartGameEvent
 *
 * @author haclong
 */
class StartGameEvent extends Event {
    const NAME = 'game.start' ;
    protected $gridSize ;
    
    public function __construct(GridSize $size)
    {
        $this->gridSize = $size ;
    }
    
    public function getGridSize()
    {
        return $this->gridSize ;
    }
}
