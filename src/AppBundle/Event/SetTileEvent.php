<?php

namespace AppBundle\Event;

use AppBundle\Entity\Event\TileSet;
use Symfony\Component\EventDispatcher\Event;

/**
 * Description of SetTileEvent
 *
 * @author haclong
 */
class SetTileEvent extends Event {
    const NAME = 'tile.set' ;
    protected $tile ;
    public function __construct(TileSet $tileSet)
    {
        $this->tile = $tileSet ;
    }
    
    public function getTile()
    {
        return $this->tile ;
    }
}
