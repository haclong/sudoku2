<?php
namespace AppBundle\Subscriber;

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
    
    public function __construct(SudokuSessionService $sessionService) {
        $this->session = $sessionService ;
    }
    
    public static function getSubscribedEvents() {
        return array(
            GetGridEvent::NAME => 'onGetGrid',
            ResetGridEvent::NAME => 'onResetGrid',
        ) ;
    }
    
    public function onGetGrid(GetGridEvent $event) {
        $this->session->saveGrid($event->getGrid()) ;
    }
    
    public function onResetGrid(ResetGridEvent $event) {
        $this->session->resetGrid() ;
    }
}
