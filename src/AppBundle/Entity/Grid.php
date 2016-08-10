<?php

namespace AppBundle\Entity;

/**
 * Description of Grid
 *
 * @author haclong
 */
class Grid {
    protected $size ;
    protected $tiles = array() ;
    protected $solved = false ;
    
    public function __construct($size)
    {
        $this->size = (int) $size ;
        
    }
    
    public function reset($size)
    {
        $this->size = $size ;
        $this->solved = false ;
        $this->tiles = array() ;
    }
    
    public function getSize()
    {
        return $this->size ;
    }
    
    public function getTiles()
    {
        return $this->tiles ;
    }
    
    public function setTiles(array $array)
    {
        $this->tiles = $array ;
    }
    
    public function solve($bool)
    {
        $this->solved = $bool ;
    }
    
    public function isSolved()
    {
        return $this->solved ;
    }
}
