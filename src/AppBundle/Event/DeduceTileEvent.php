<?php

namespace AppBundle\Event;

use AppBundle\Entity\Event\TileLastPossibility;
use Symfony\Component\EventDispatcher\Event;

/**
 * Description of DeduceTileEvent
 *
 * @author haclong
 */
class DeduceTileEvent extends Event {
    const NAME = 'tile.deduce' ;
    protected $tile ;

    public function __construct(TileLastPossibility $tile) {
        $this->tile = $tile;
    }

    public function getTile() {
        return $this->tile;
    }
}
