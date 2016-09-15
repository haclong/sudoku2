<?php

namespace AppBundle\Subscriber;

use AppBundle\Entity\Tiles;
use AppBundle\Event\ChooseGameEvent;
use AppBundle\Event\LoadGameEvent;
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
            ChooseGameEvent::NAME => 'onChooseGame',
            LoadGameEvent::NAME => array('onLoadGame', -500),
        ) ;
    }
    
    protected function getTilesFromSession() {
        return $this->session->getTiles() ;
    }
    protected function storeTiles(Tiles $tiles) {
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
}
