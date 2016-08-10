<?php

namespace AppBundle\Event;

use AppBundle\Entity\Event\TileLastPossibility;
use Symfony\Component\EventDispatcher\Event;

/**
 * Description of LastPossibilityEvent
 *
 * @author haclong
 */
class LastPossibilityEvent extends Event {
    const NAME = 'tile.lastPossibility' ;
    protected $tile ;

    public function __construct(TileLastPossibility $tile) {
        $this->tile = $tile;
    }

    public function getTile() {
        return $this->tile;
    }
}
