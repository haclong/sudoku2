<?php

namespace AppBundle\Utils;

use AppBundle\Entity\Grid;
use AppBundle\Entity\Tiles;
use AppBundle\Entity\Values;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Description of SudokuSession
 *
 * @author haclong
 */
class SudokuSession {
    protected $session ;
    
    public function __construct(Session $session) {
        $this->session = $session;
    }
    
    public function getSession()
    {
        return $this->session ;
    }
    
    public function isReady()
    {
        $flag = 0 ;
        //echo get_class($this->getGrid()) ;
        if(!$this->getGrid() instanceof Grid) {
            $flag += 1 ;
        }
        //echo get_class($this->getValues()) ;
        if(!$this->getValues() instanceof Values) {
            $flag += 1 ;
        }
        //echo get_class($this->getTiles()) ;
        if(!$this->getTiles() instanceof Tiles) {
            $flag += 1 ;
        }
        $result = ($flag == 0) ? true  : false ;
        
        return $result ;
    }
    
    public function clear()
    {
        $this->session->clear() ;
    }
    
    public function getGrid()
    {
        return $this->session->get('grid') ;
    }
    public function setGrid(Grid $grid)
    {
        $this->session->set('grid', $grid) ;
    }

    public function getValues()
    {
        return $this->session->get('values') ;
    }
    public function setValues(Values $values)
    {
        $this->session->set('values', $values) ;
    }

    public function getTiles()
    {
        return $this->session->get('tiles') ;
    }
    public function setTiles(Tiles $tiles)
    {
        $this->session->set('tiles', $tiles) ;
    }
    
    public function getTilesToSolved()
    {
        return $this->session->get('tilesToSolved') ;
    }
    public function setTilesToSolved($tiles)
    {
        $this->session->set('tilesToSolved', $tiles) ;
    }
    protected function resetTilesToSolved()
    {
        if($this->session->has('tilesToSolved'))
        {
            $this->getTilesToSolved()->exchangeArray(array()) ;
        }
        $this->session->set('tilesToSolved', $this->tilesToSolved) ;
    }
}