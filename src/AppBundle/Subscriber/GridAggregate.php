<?php

namespace AppBundle\Subscriber;

use AppBundle\Entity\Grid;
use AppBundle\Event\GetGridEvent;
use AppBundle\Event\ResetGridEvent;
use AppBundle\Service\SudokuSessionService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Description of GridAggregate
 *
 * @author haclong
 */
class GridAggregate implements EventSubscriberInterface {
    protected $session ;
    
    public function __construct(SudokuSessionService $sessionService, Grid $grid) {
        $this->session = $sessionService ;
        $this->storeGrid($grid) ;
    }
    
    public static function getSubscribedEvents() {
        return array(
            GetGridEvent::NAME => 'onGetGrid',
            ResetGridEvent::NAME => 'onResetGrid',
        ) ;
    }
    
    protected function getGridFromSession() {
        return $this->session->getGrid() ;
    }
    protected function storeGrid(Grid $grid) {
        $this->session->saveGrid($grid) ;
    }

    public function onGetGrid(GetGridEvent $event) {
        $this->session->saveGrid($event->getGrid()) ;
    }
    
    public function onResetGrid(ResetGridEvent $event) {
        $this->session->resetGrid() ;
    }
}
