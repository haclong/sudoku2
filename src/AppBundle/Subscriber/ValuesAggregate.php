<?php

namespace AppBundle\Subscriber;

use AppBundle\Entity\Values;
use AppBundle\Event\LoadGameEvent;
use AppBundle\Event\ResetGameEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Description of ValueAggregate
 *
 * @author haclong
 */
class ValuesAggregate implements EventSubscriberInterface {
    protected $session ;

    public function __construct(Session $sessionService) {
        $this->session = $sessionService ;
    }

    public static function getSubscribedEvents() {
        return array(
            LoadGameEvent::NAME => array('onLoadGame', 2048),
            ResetGameEvent::NAME => 'onResetGame',
        ) ;
    }

    protected function getValuesFromSession() {
        return $this->session->get('values') ;
    }
    protected function storeValues(Values $values) {
        $this->session->set('values', $values) ;
    }
    
    public function onResetGame(ResetGameEvent $event) {
        $values = $this->getValuesFromSession() ;
        $values->reset() ;
        $this->storeValues($values) ;
    }
    
    public function onLoadGame(LoadGameEvent $event) {
        $values = $this->getValuesFromSession() ;
        $tiles = $event->getTiles() ;
        
        $values->setGridSize($tiles->getSize()) ;
        foreach($tiles->getTiles() as $row) 
        {
            foreach($row as $col)
            {
                $values->add($col) ;
            }
            
        }
        $this->storeValues($values) ;
    }
}