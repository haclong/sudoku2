<?php


namespace AppBundle\Event;

use AppBundle\Entity\Event\TilesLoaded;
use Symfony\Component\EventDispatcher\Event;

/**
 * Description of GetGridEvent
 *
 * @author haclong
 */
class GetGridEvent extends Event {
    const NAME = 'grid.get' ;
    protected $tiles ;
    public function __construct(TilesLoaded $tiles)
    {
        $this->tiles = $tiles ;
    }
    
    public function getTiles()
    {
        return $this->tiles ;
    }
}
