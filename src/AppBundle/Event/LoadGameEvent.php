<?php


namespace AppBundle\Event;

use AppBundle\Entity\Event\TilesLoaded;
use Symfony\Component\EventDispatcher\Event;

/**
 * Description of LoadGameEvent
 *
 * @author haclong
 */
class LoadGameEvent extends Event {
    const NAME = 'game.load' ;
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
