<?php

namespace AppBundle\Persistence;

use AppBundle\Entity\Values;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Description of ValuesSession
 *
 * @author haclong
 */
class ValuesSession implements IsReadyInterface {
    protected $session ;
    
    public function __construct(Session $session) {
        $this->session = $session;
    }
    public function getValues()
    {
        return $this->session->get('values') ;
    }
    public function setValues(Values $values)
    {
        $this->session->set('values', $values) ;
    }
    public function isReady()
    {
        //echo get_class($this->getValues()) ;
        if(!$this->getValues() instanceof Values) {
            return false ;
        }
        return true ;
    }
}
