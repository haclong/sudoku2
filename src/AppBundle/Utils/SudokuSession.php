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
    
    public function __construct(Session $session, Grid $grid, Values $values, Tiles $tiles) {
        $this->session = $session;
        $this->grid = $grid ;
        $this->values = $values;
        $this->tiles = $tiles;
    }
    
    public function clear()
    {
        $this->session->clear() ;
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
        $this->session->set('tiles', $tiles) ;
    }

}
