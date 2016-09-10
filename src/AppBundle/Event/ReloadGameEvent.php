<?php

namespace AppBundle\Event;

use AppBundle\Entity\Grid;
use Symfony\Component\EventDispatcher\Event;

/**
 * Description of ReloadGameEvent
 *
 * @author haclong
 */
class ReloadGameEvent extends Event {
    const NAME = 'game.reload' ;
    protected $grid ;
    
    public function __construct(Grid $grid)
    {
        $this->grid = $grid ;
    }
    
    public function getGrid()
    {
        return $this->grid ;
    }
}
