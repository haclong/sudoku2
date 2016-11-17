<?php

namespace AppBundle\Subscriber;

use AppBundle\Entity\Grid;
use AppBundle\Event\InitGameEvent;
use AppBundle\Event\LoadGameEvent;
use AppBundle\Event\ReloadGameEvent;
use AppBundle\Event\ResetGameEvent;
use AppBundle\Event\SetGameEvent;
use AppBundle\Event\ValidateTileSetEvent;
use AppBundle\Persistence\GridSession;
use AppBundle\Service\SetTileService;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


/**
 * Description of GridAggregate
 *
 * @author haclong
 */
class GridAggregate implements EventSubscriberInterface {
    protected $session ;
    protected $service ;
    
    public function __construct(GridSession $sessionService, SetTileService $setTileService) {
        $this->session = $sessionService ;
        $this->service = $setTileService ;
    }
    
    public static function getSubscribedEvents() {
        return array(
            SetGameEvent::NAME => 'onSetGame',
            InitGameEvent::NAME => 'onInitGame',
            LoadGameEvent::NAME => 'onLoadGame',
            ReloadGameEvent::NAME => array('onReloadGame', -10),
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
    
    public function onInitGame(InitGameEvent $event) {
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
        foreach($event->getTiles()->getTiles() as $row => $cols)
        {
            foreach($cols as $col => $value)
            {
                $this->service->setTile($row, $col, $value) ;
            }
        }
        
        $this->storeGrid($grid) ;
    }
    
    public function onReloadGame(ReloadGameEvent $event) {
        $grid = $this->getGridFromSession() ;
        $grid->reload() ;
        foreach($grid->getTiles() as $row => $cols)
        {
            foreach($cols as $col => $value)
            {
                $this->service->setTile($row, $col, $value) ;
            }
        }
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
        $validTile = $event->getTile() ;
        $grid->storeMove($validTile->getRow(), $validTile->getCol(), $validTile->getValue(), $event->isConfirmed()) ;
        $this->storeGrid($grid) ;
    }
}