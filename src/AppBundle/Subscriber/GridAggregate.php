<?php

namespace AppBundle\Subscriber;

use AppBundle\Event\GetGridEvent;
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
        ) ;
    }
    
    public function onGetGrid(GetGridEvent $event) {
        $this->session->saveGrid($event->getGrid()) ;
//        var_dump($this->session->getGrid()) ;
//        echo get_class($this->session) ;
    }
}
