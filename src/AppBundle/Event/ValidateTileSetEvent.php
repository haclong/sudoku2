<?php

/*
 * Event dispatched once tiles in group has been discarded
 */

namespace AppBundle\Event;
use AppBundle\Entity\Event\TileSet;
use Symfony\Component\EventDispatcher\Event;

/**
 * Description of TileSetValidatedEvent
 *
 * @author haclong
 */
class ValidateTileSetEvent extends Event {
    const NAME = 'settile.validated' ;
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
