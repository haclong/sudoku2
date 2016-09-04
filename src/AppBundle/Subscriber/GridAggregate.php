<?php

namespace AppBundle\Subscriber;

use AppBundle\Entity\Grid;
use AppBundle\Event\ChooseGridEvent;
use AppBundle\Event\ClearGridEvent;
use AppBundle\Event\GetGridEvent;
use AppBundle\Event\ResetGridEvent;
use AppBundle\Event\StartGameEvent;
use AppBundle\Service\SudokuSessionService;
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
            ChooseGridEvent::NAME => 'onChooseGrid',
            GetGridEvent::NAME => 'onGetGrid',
            ResetGridEvent::NAME => 'onResetGrid',
            ClearGridEvent::NAME => 'onClearGrid',
        ) ;
    }
    
    protected function getGridFromSession() {
        return $this->session->get('grid') ;
    }
    protected function storeGrid(Grid $grid) {
        $this->session->set('grid', $grid) ;
    }
    
    public function onChooseGrid(ChooseGridEvent $event) {
        $grid = $this->getGridFromSession() ;
        $grid->newGrid() ;
        $grid->init($event->getGridSize()->get()) ;
        $this->storeGrid($grid) ;
    }
    
    public function onGetGrid(GetGridEvent $event) {
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
    
    public function onResetGrid(ResetGridEvent $event) {
        $grid = $this->getGridFromSession() ;
        $grid->reset() ;
        $this->storeGrid($grid) ;
    }

    public function onClearGrid(ClearGridEvent $event) {
        $grid = $this->getGridFromSession() ;
        $size = $grid->getSize() ;
        $grid->newGrid() ;
        $grid->init($size) ;
        $this->storeGrid($grid) ;
    }
}