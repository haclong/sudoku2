<?php

namespace AppBundle\Persistence;

use AppBundle\Entity\Tiles;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Description of TilesSession
 *
 * @author haclong
 */
class TilesSession implements IsReadyInterface {
    protected $session ;
    
    public function __construct(Session $session) {
        $this->session = $session;
    }

    public function getTiles()
    {
        return $this->session->get('tiles') ;
    }
    public function setTiles(Tiles $tiles)
    {
        $this->session->set('tiles', $tiles) ;
    }
    public function isReady()
    {
        //echo get_class($this->getTiles()) ;
        if(!$this->getTiles() instanceof Tiles) {
            return false ;
        }
        return true ;
    }
}
