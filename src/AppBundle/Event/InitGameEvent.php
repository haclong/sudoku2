<?php

namespace AppBundle\Event;

use AppBundle\Entity\Event\GridSize;
use Symfony\Component\EventDispatcher\Event;
/**
 * Description of InitGridEvent
 *
 * @author haclong
 */
class InitGameEvent extends Event {
    const NAME = 'game.init' ;
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
