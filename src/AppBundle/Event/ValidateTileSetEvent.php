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
    protected $isConfirmed ;
    public function __construct(TileSet $tileSet)
    {
        $this->tile = $tileSet ;
        $this->isConfirmed = true ;
    }
    
    public function getTile()
    {
        return $this->tile ;
    }
    public function setConfirmation($isConfirmed)
    {
        $this->isConfirmed = $isConfirmed ;
    }
    
    public function isConfirmed()
    {
        return $this->isConfirmed ;
    }
}
