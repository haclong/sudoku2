<?php

namespace AppBundle\Entity;

use AppBundle\Exception\InvalidGridSizeException;

/**
 * Description of Grid
 *
 * @author haclong
 */
class Grid {
    protected $size ;
    protected $tiles = array() ;
    protected $solved = false ;
    protected $remainingTiles ;
    
    public function init($size)
    {
        $gridSize = (int) $size ;
//        try {
            $this->validateGridSize($gridSize) ;
            $this->size = $gridSize ;
            $this->remainingTiles = $size * $size ;
//        } catch (InvalidGridSizeException $ex) {
//        }
    }
    
    public function reset()
    {
        $this->solved = false ;
        $this->remainingTiles = $this->size * $this->size ;
    }
    
    public function newGrid()
    {
        $this->solved = false ;
        $this->remainingTiles = null ;
        $this->tiles = array() ;
        $this->size = null ;
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

    protected function validateGridSize($size)
    {
        $root = sqrt($size) ;
        if(fmod($root, 1) != 0) 
        {
            throw new InvalidGridSizeException('Invalid grid size : ' . $size) ;
        }        
    }
}
