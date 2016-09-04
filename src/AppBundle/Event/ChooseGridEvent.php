<?php

namespace AppBundle\Event;

use AppBundle\Entity\Event\GridSize;
use Symfony\Component\EventDispatcher\Event;
/**
 * Description of ChooseGridEvent
 *
 * @author haclong
 */
class ChooseGridEvent extends Event {
    const NAME = 'grid.choose' ;
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
