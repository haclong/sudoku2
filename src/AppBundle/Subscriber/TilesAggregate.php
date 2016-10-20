<?php

namespace AppBundle\Subscriber;

use AppBundle\Entity\Tiles;
use AppBundle\Event\DeduceTileEvent;
use AppBundle\Event\InitGameEvent;
use AppBundle\Event\ReloadGameEvent;
use AppBundle\Event\ResetGameEvent;
use AppBundle\Event\SetGameEvent;
use AppBundle\Event\ValidateTileSetEvent;
use AppBundle\Persistence\TilesSession;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Description of TilesAggregate
 *
 * @author haclong
 */
class TilesAggregate implements EventSubscriberInterface{
    protected $session ;
    
    public function __construct(TilesSession $session)
    {
        $this->session = $session ;
    }
    
    public static function getSubscribedEvents() {
        return array(
            SetGameEvent::NAME => 'onSetGame',
            InitGameEvent::NAME => 'onInitGame',
            ReloadGameEvent::NAME => array('onReloadGame', 10),
            ResetGameEvent::NAME => 'onResetGame',
            ValidateTileSetEvent::NAME => 'onValidatedTile',
            DeduceTileEvent::NAME => 'onDeduceTile', 
        ) ;
    }
    
    protected function getTilesFromSession() {
        return $this->session->getTiles() ;
    }
    protected function storeTiles(Tiles $tiles) {
        $this->session->setTiles($tiles) ;
    }
    
    public function onSetGame(SetGameEvent $event) {
        $tiles = $event->getEntity('tilesentity') ;
        $tiles->reset() ;
        $this->session->setTiles($tiles) ;
    }

    public function onInitGame(InitGameEvent $event) {
        $tiles = $this->getTilesFromSession() ;
        $tiles->reset() ;
        $tiles->init($event->getGridSize()->get()) ;
        $this->storeTiles($tiles) ;
    }
    
    public function onReloadGame(ReloadGameEvent $event) {
        $tiles = $this->getTilesFromSession() ;
        $grid = $event->getGrid() ;
        $tiles->reload($grid) ;
        $this->storeTiles($tiles) ;
    }

    public function onResetGame(ResetGameEvent $event) {
        $tiles = $this->getTilesFromSession() ;
        $size = $tiles->getSize() ;
        $tiles->reset() ;
        $tiles->init($size) ;
        $this->storeTiles($tiles) ;
    }

    public function onValidatedTile(ValidateTileSetEvent $event) {
        $tiles = $this->getTilesFromSession() ;
        
        $validTile = $event->getTile() ;
        $tiles->set($validTile->getRow(), $validTile->getCol(), $validTile->getValue()) ;
        $this->storeTiles($tiles) ;
    }
    
    public function onDeduceTile(DeduceTileEvent $event) {
        $tiles = $this->getTilesFromSession() ;
        
        $tiles->priorizeTileToSolve($event->getTile()) ;
        $this->storeTiles($tiles) ;
    }
}
