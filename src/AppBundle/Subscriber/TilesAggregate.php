<?php

namespace AppBundle\Subscriber;

use AppBundle\Entity\Tiles;
use AppBundle\Event\InitGameEvent;
use AppBundle\Event\SetGameEvent;
use AppBundle\Event\LoadGameEvent;
use AppBundle\Event\ReloadGameEvent;
use AppBundle\Event\ResetGameEvent;
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
//            LoadGameEvent::NAME => array('onLoadGame', -500),
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
//    
//    public function onLoadGame(LoadGameEvent $event) {
//        $tiles = $this->getTilesFromSession() ;
//
//        $loadedTiles = $event->getTiles()->getTiles() ;
//        foreach($loadedTiles as $row => $cols)
//        {
//            foreach($cols as $col => $value)
//            {
//                $tiles->set($row, $col, $value) ;
//            }
//        }
//        $this->storeTiles($tiles) ;
//    }
    
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
}
