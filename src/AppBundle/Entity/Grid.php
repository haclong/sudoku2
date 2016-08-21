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
    protected $remainingTiles = array() ;
    
    
    public function __construct($size)
    {
        $this->size = (int) $size ;
        $this->remainingTiles = $size * $size ;
    }
    
    public function reset()
    {
        $this->solved = false ;
        $this->remainingTiles = $this->size * $this->size ;
    }
    
    public function getSize()
    {
        return $this->size ;
    }
    
    public function getTiles()
    {
        return $this->tiles ;
    }
    
    public function getRemainingTiles()
    {
        return $this->remainingTiles ;
    }
    
    public function setTiles(array $array)
    {
        $this->tiles = $array ;
    }
//    
//    public function solve($bool)
//    {
//        $this->solved = $bool ;
//    }
    
    public function decreaseRemainingTiles()
    {
        $this->remainingTiles -= 1 ;
    }
            
    public function isSolved()
    {
        if($this->remainingTiles == 0)
        {
            return true ;
        }
        return $this->solved ;
    }
}
