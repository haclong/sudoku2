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
    protected $grid ;
    protected $values ; 
    protected $tiles ;
    
    public function __construct(Session $session) {
        $this->session = $session;
    }
    
    public function clear()
    {
        $this->session->clear() ;
        $this->grid = null ;
        $this->values = null ;
        $this->tiles = null ;
    }
    
    public function getGrid()
    {
        if(!$this->session->has('grid'))
        {
            $this->setGrid($this->grid) ;
        }
        return $this->session->get('grid') ;
    }
    public function setGrid(Grid $grid)
    {
        if(empty($this->grid))
        {
            $this->grid = $grid ;
        }
        $this->session->set('grid', $grid) ;
    }

    public function getValues()
    {
        if(!$this->session->has('values'))
        {
            $this->setValues($this->values) ;
        }
        return $this->session->get('values') ;
    }
    public function setValues(Values $values)
    {
        if(empty($this->values))
        {
            $this->values = $values ;
        }
        $this->session->set('values', $values) ;
    }

    public function getTiles()
    {
        if(!$this->session->has('tiles'))
        {
            $this->setTiles($this->tiles) ;
        }
        return $this->session->get('tiles') ;
    }
    public function setTiles(Tiles $tiles)
    {
        if(empty($this->tiles))
        {
            $this->tiles = $tiles ;
        }
        $this->session->set('tiles', $tiles) ;
    }
}
