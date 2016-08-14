<?php


namespace AppBundle\Event;

use AppBundle\Entity\Grid;
use Symfony\Component\EventDispatcher\Event;

/**
 * Description of GetGridEvent
 *
 * @author haclong
 */
class GetGridEvent extends Event {
    const NAME = 'grid.get' ;
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
