<?php

namespace AppBundle\Subscriber;

use AppBundle\Entity\Values;
use AppBundle\Event\GetGridEvent;
use AppBundle\Event\ResetGridEvent;
use AppBundle\Service\SudokuSessionService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Description of ValueAggregate
 *
 * @author haclong
 */
class ValuesAggregate implements EventSubscriberInterface {
    protected $session ;

    public function __construct(SudokuSessionService $sessionService, Values $values) {
        $this->session = $sessionService ;
        $this->storeValues($values) ;
    }
    
    public static function getSubscribedEvents() {
        return array(
            GetGridEvent::NAME => array('onGetGrid', 2048),
        ) ;
    }
    
    public function onResetGrid() {
        $this->session->resetValues() ;
    }

    protected function getValuesFromSession() {
        return $this->session->getValues() ;
    }
    protected function storeValues(Values $values) {
        $this->session->saveValues($values) ;
    }
    
    public function onGetGrid(GetGridEvent $event) {
        $values = $this->getValuesFromSession() ;
        $grid = $event->getGrid() ;
        
        $values->setGridSize($grid->getSize()) ;
        foreach($grid->getTiles() as $row) 
        {
            foreach($row as $col)
            {
                $values->add($col) ;
            }
            
        }
        $this->storeValues($values) ;
    }
}
