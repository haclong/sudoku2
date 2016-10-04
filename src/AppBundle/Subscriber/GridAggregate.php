<?php

namespace AppBundle\Subscriber;

use AppBundle\Entity\Grid;
use AppBundle\Event\ChooseGameEvent;
use AppBundle\Event\SetGameEvent;
use AppBundle\Event\LoadGameEvent;
use AppBundle\Event\ReloadGameEvent;
use AppBundle\Event\ResetGameEvent;
use AppBundle\Event\ValidateTileSetEvent;
use AppBundle\Persistence\GridSession;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


/**
 * Description of GridAggregate
 *
 * @author haclong
 */
class GridAggregate implements EventSubscriberInterface {
    protected $session ;
    
    public function __construct(GridSession $sessionService) {
        $this->session = $sessionService ;
    }
    
    public static function getSubscribedEvents() {
        return array(
            SetGameEvent::NAME => 'onSetGame',
            ChooseGameEvent::NAME => 'onChooseGame',
            LoadGameEvent::NAME => 'onLoadGame',
            ReloadGameEvent::NAME => 'onReloadGame',
            ResetGameEvent::NAME => 'onResetGame',
            ValidateTileSetEvent::NAME => 'onValidatedTile',
        ) ;
    }

    protected function getGridFromSession() {
        return $this->session->getGrid() ;
    }
    protected function storeGrid(Grid $grid) {
        $this->session->setGrid($grid) ;
    }
    
    public function onSetGame(SetGameEvent $event) {
        $grid = $event->getEntity('gridentity') ;
        $grid->reset() ;
        $this->session->setGrid($grid) ;
    }
    
    public function onChooseGame(ChooseGameEvent $event) {
        $grid = $this->getGridFromSession() ;
        $grid->reset() ;
        $grid->init($event->getGridSize()->get()) ;
        $this->storeGrid($grid) ;
    }
    
    public function onLoadGame(LoadGameEvent $event) {
        $grid = $this->getGridFromSession() ;

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

    public function onValidatedTile(ValidateTileSetEvent $event) {
        $grid = $this->getGridFromSession() ;
        $grid->decreaseRemainingTiles() ;
        $this->storeGrid($grid) ;
    }
}