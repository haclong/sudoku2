<?php

namespace AppBundle\Event;

use AppBundle\Entity\Grid;
use AppBundle\Entity\Tiles;
use AppBundle\Entity\Values;
use Symfony\Component\EventDispatcher\Event;

/**
 * Description of InitGameEvent
 *
 * @author haclong
 */
class InitGameEvent extends Event {
    const NAME = 'game.init' ;
    protected $grid ;
    protected $values ;
    protected $tiles ;
    
    public function __construct(Grid $grid, Values $values, Tiles $tiles)
    {
        $this->grid = $grid ;
        $this->values = $values ;
        $this->tiles = $tiles ;
    }
    
    public function getGrid() {
        return $this->grid;
    }

    public function getValues() {
        return $this->values;
    }

    public function getTiles() {
        return $this->tiles;
    }

}
