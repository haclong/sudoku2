<?php

namespace AppBundle\Subscriber;

use AppBundle\Event\ChooseGameEvent;
use AppBundle\Event\InitGameEvent;
use AppBundle\Event\LoadGameEvent;
use AppBundle\Event\ReloadGameEvent;
use AppBundle\Event\ResetGameEvent;
use AppBundle\Service\ValuesService;
use AppBundle\Utils\SudokuSession;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Description of GroupsAggregate
 *
 * @author haclong
 */
class GroupsAggregate implements EventSubscriberInterface{
    protected $session ;
    protected $service ;
    
    public function __construct(SudokuSession $sessionService, ValuesService $service) {
        $this->session = $sessionService ;
        $this->service = $service ;
    }

    public static function getSubscribedEvents() {
        return array(
            InitGameEvent::NAME => 'onInitGame',
            ChooseGameEvent::NAME => 'onChooseGame',
            LoadGameEvent::NAME => 'onLoadGame',
            ReloadGameEvent::NAME => 'onReloadGame',
            ResetGameEvent::NAME => 'onResetGame',
        ) ;
    }
    
    protected function getGroupsFromSession() {
        return $this->session->getGroups() ;
    }
    protected function storeGroups(Groups $groups) {
        $this->session->setGroups($groups) ;
    }
    
//    public function onInitGame(InitGameEvent $event) {
//        $tiles = $event->getTiles() ;
//        $tiles->reset() ;
//        $this->session->setTiles($tiles) ;
//    }
//
//    public function onChooseGame(ChooseGameEvent $event) {
//        $tiles = $this->getTilesFromSession() ;
//        $tiles->reset() ;
//        $tiles->init($event->getGridSize()->get()) ;
//        $this->storeTiles($tiles) ;
//    }
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
//    
//    public function onReloadGame(ReloadGameEvent $event) {
//        $tiles = $this->getTilesFromSession() ;
//        $grid = $event->getGrid() ;
//        $tiles->reload($grid) ;
//        $this->storeTiles($tiles) ;
//    }
//
//    public function onResetGame(ResetGameEvent $event) {
//        $tiles = $this->getTilesFromSession() ;
//        $size = $tiles->getSize() ;
//        $tiles->reset() ;
//        $tiles->init($size) ;
//        $this->storeTiles($tiles) ;
//    }
}