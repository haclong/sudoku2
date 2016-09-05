<?php

namespace AppBundle\Subscriber;

use AppBundle\Entity\Grid;
use AppBundle\Event\ChooseGameEvent;
use AppBundle\Event\ResetGameEvent;
use AppBundle\Event\LoadGameEvent;
use AppBundle\Event\ReloadGameEvent;
use AppBundle\Event\StartGameEvent;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;


/**
 * Description of GridAggregate
 *
 * @author haclong
 */
class GridAggregate implements EventSubscriberInterface {
    protected $session ;
    
    public function __construct(Session $sessionService) {
        $this->session = $sessionService ;
    }
    
    public static function getSubscribedEvents() {
        return array(
//            StartGameEvent::NAME => 'onStartGame',
            ChooseGameEvent::NAME => 'onChooseGame',
            LoadGameEvent::NAME => 'onLoadGame',
            ReloadGameEvent::NAME => 'onReloadGame',
            ResetGameEvent::NAME => 'onResetGame',
        ) ;
    }
    
    protected function getGridFromSession() {
        return $this->session->get('grid') ;
    }
    protected function storeGrid(Grid $grid) {
        $this->session->set('grid', $grid) ;
    }
    
    public function onChooseGame(ChooseGameEvent $event) {
        $grid = $this->getGridFromSession() ;
        $grid->reset() ;
        $grid->init($event->getGridSize()->get()) ;
        $this->storeGrid($grid) ;
    }
    
    public function onLoadGame(LoadGameEvent $event) {
        $grid = $this->getGridFromSession() ;
        //echo $event->getTiles()->getSize() ;
        //die ;
        if($grid->getSize() != $event->getTiles()->getSize())
        {
            throw new RuntimeException('event grid size differs from session grid size') ;
        }
        $grid->setTiles($event->getTiles()->getTiles()) ;
        $this->storeGrid($grid) ;
    }
    
    public function onReloadGame(ReloadGameEvent $event) {
        $grid = $this->getGridFromSession() ;
        $grid->reload() ;
        $this->storeGrid($grid) ;
    }

    public function onResetGame(ResetGameEvent $event) {
        $grid = $this->getGridFromSession() ;
        $size = $grid->getSize() ;
        $grid->reset() ;
        $grid->init($size) ;
        $this->storeGrid($grid) ;
    }
}