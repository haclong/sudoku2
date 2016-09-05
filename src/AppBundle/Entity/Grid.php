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
    protected $remainingTiles = -1 ;
    
    public function init($size)
    {
        $gridSize = (int) $size ;
//        try {
            $this->validateGridSize($gridSize) ;
            $this->setSize($gridSize) ;
            $this->reloadRemainingTiles() ;
//        } catch (InvalidGridSizeException $ex) {
//        }
    }
    public function reload()
    {
        $this->reloadRemainingTiles() ;
    }
    public function reset()
    {
        $this->resetRemainingTiles() ;
        $this->resetTiles() ;
        $this->resetSize() ;
    }
    
    protected function setSize($size)
    {
        $this->size = $size ;
    }
    protected function resetSize()
    {
        $this->size = null ;
    }
    public function getSize()
    {
        return $this->size ;
    }
    
    public function setTiles(array $array)
    {
        $this->tiles = $array ;
    }
    public function getTiles()
    {
        return $this->tiles ;
    }
    protected function resetTiles()
    {
        $this->tiles = [] ;
    }
    
    public function getRemainingTiles()
    {
        return $this->remainingTiles ;
    }
    protected function reloadRemainingTiles()
    {
        $this->remainingTiles = $this->size * $this->size ;
    }
    protected function resetRemainingTiles()
    {
        $this->remainingTiles = -1 ;
    }
    public function decreaseRemainingTiles()
    {
        $this->remainingTiles -= 1 ;
    }
    public function increaseRemainingTiles()
    {
        if($this->remainingTiles == $this->size * $this->size)
        {
            throw new MaxRemainingTilesLimitException() ;
        }
        $this->remainingTiles += 1 ;
    }
            
    public function isSolved()
    {
        if($this->remainingTiles == 0)
        {
            return true ;
        }
        return false ;
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
