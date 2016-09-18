<?php

namespace AppBundle\Subscriber;

use AppBundle\Entity\Tiles;
use AppBundle\Event\ChooseGameEvent;
use AppBundle\Event\InitGameEvent;
use AppBundle\Event\LoadGameEvent;
use AppBundle\Event\ReloadGameEvent;
use AppBundle\Event\ResetGameEvent;
use AppBundle\Service\TileService;
use AppBundle\Utils\SudokuSession;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Description of TilesAggregate
 *
 * @author haclong
 */
class TilesAggregate implements EventSubscriberInterface{
    protected $session ;
    protected $service ;
    
    public function __construct(SudokuSession $session, TileService $service)
    {
        $this->session = $session ;
        $this->service = $service ;
    }
    
    public static function getSubscribedEvents() {
        return array(
            InitGameEvent::NAME => 'onInitGame',
            ChooseGameEvent::NAME => 'onChooseGame',
            LoadGameEvent::NAME => array('onLoadGame', -500),
            ReloadGameEvent::NAME => 'onReloadGame',
            ResetGameEvent::NAME => 'onResetGame',
        ) ;
    }
    
    protected function getTilesFromSession() {
        return $this->session->getTiles() ;
    }
    protected function storeTiles(Tiles $tiles) {
        $this->session->setTiles($tiles) ;
    }
    
    public function onInitGame(InitGameEvent $event) {
        $tiles = $event->getTiles() ;
        $tiles->reset() ;
        $this->session->setTiles($tiles) ;
    }

    public function onChooseGame(ChooseGameEvent $event) {
        $tiles = $this->getTilesFromSession() ;
        $tiles->reset() ;
        $tiles->setTileset($event->getGridSize()->get()) ;
        $this->storeTiles($tiles) ;
    }
    
    public function onLoadGame(LoadGameEvent $event) {
        $tiles = $this->getTilesFromSession() ;
        $this->service->setValues($this->session->getValues()) ;

        $loadedTiles = $event->getTiles()->getTiles() ;
        foreach($loadedTiles as $row => $cols)
        {
            foreach($cols as $col => $value)
            {
                $getTile = $tiles->getTile($row, $col) ;
                $this->service->set($getTile, $value) ;
            }
        }
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
        $tiles->setTileset($size) ;
        $this->storeTiles($tiles) ;
    }
}
