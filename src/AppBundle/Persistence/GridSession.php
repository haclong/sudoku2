<?php

namespace AppBundle\Persistence;

use AppBundle\Entity\Grid;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Description of GridSession
 *
 * @author haclong
 */
class GridSession implements IsReadyInterface {
    protected $session ;
    
    public function __construct(Session $session) {
        $this->session = $session;
    }
    public function getGrid()
    {
        return $this->session->get('grid') ;
    }
    public function setGrid(Grid $grid)
    {
        $this->session->set('grid', $grid) ;
    }
    public function isReady()
    {
        //echo get_class($this->getGrid()) ;
        if(!$this->getGrid() instanceof Grid) {
            return false ;
        }
        return true ;
    }
}
