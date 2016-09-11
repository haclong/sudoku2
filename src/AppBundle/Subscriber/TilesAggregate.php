<?php

namespace AppBundle\Subscriber;

use AppBundle\Entity\Tiles;
use AppBundle\Event\ChooseGameEvent;
use AppBundle\Event\LoadGameEvent;
use AppBundle\Utils\SudokuSession;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Description of TilesAggregate
 *
 * @author haclong
 */
class TilesAggregate implements EventSubscriberInterface{
    protected $session ;
    
    public function __construct(SudokuSession $session)
    {
        $this->session = $session ;
    }
    
    public static function getSubscribedEvents() {
        return array(
            ChooseGameEvent::NAME => 'onChooseGame',
            LoadGameEvent::NAME => 'onLoadGame',
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
//        $tiles = $this->getTilesFromSession() ;
//
//        $mappedTiles = $this->mapTiles($event->getTiles()->getTiles()) ;
//        $tiles->setTiles($mappedTiles) ;
//        $this->storeTiles($tiles) ;
    }
    
    protected function mapTiles($tiles)
    {
        $values = $this->session->getValues() ;
        $mappedTiles = array() ;
        foreach($tiles as $row => $cols)
        {
            foreach($cols as $col => $value)
            {
                $mappedTiles[$row][$col] = $values->getKeyByValue($value) ;
            }
        }
        
        return $mappedTiles ;
    }
}
