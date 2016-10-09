<?php

namespace AppBundle\Subscriber;

use AppBundle\Entity\Groups;
use AppBundle\Event\InitGameEvent;
use AppBundle\Event\ReloadGameEvent;
use AppBundle\Event\ResetGameEvent;
use AppBundle\Event\SetGameEvent;
use AppBundle\Event\SetTileEvent;
use AppBundle\Persistence\GroupsSession;
use AppBundle\Service\GroupsService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Description of GroupsAggregate
 *
 * @author haclong
 */
class GroupsAggregate implements EventSubscriberInterface{
    protected $session ;
    
    public function __construct(GroupsSession $session) {
        $this->session = $session ;
    }

    public static function getSubscribedEvents() {
        return array(
            SetGameEvent::NAME => 'onSetGame',
            InitGameEvent::NAME => 'onInitGame',
//            LoadGameEvent::NAME => 'onLoadGame',
            ReloadGameEvent::NAME => 'onReloadGame',
            ResetGameEvent::NAME => 'onResetGame'
        ) ;
    }
    
    protected function getGroupsFromSession() {
        return $this->session->getGroups() ;
    }
    protected function storeGroups(Groups $groups) {
        $this->session->setGroups($groups) ;
    }
    
    public function onSetGame(SetGameEvent $event) {
        $groups = $event->getEntity('groupsentity') ;
        $groups->reset() ;
        $this->session->setGroups($groups) ;
    }

    public function onInitGame(InitGameEvent $event) {
        $groups = $this->getGroupsFromSession() ;
        $groups->reset() ;
        $groups->init($event->getGridSize()->get()) ;
        $this->storeGroups($groups) ;
    }

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
        $groups = $this->getGroupsFromSession() ;
        $grid = $event->getGrid() ;
        $groups->reload($grid) ;
        $this->storeGroups($groups) ;
    }

    public function onResetGame(ResetGameEvent $event) {
        $groups = $this->getGroupsFromSession() ;
        $size = $groups->getSize() ;
        $groups->reset() ;
        $groups->init($size) ;
        $this->storeGroups($groups) ;
    }
}