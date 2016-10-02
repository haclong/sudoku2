<?php

namespace AppBundle\Entity;

use AppBundle\Exception\InvalidGridSizeException;
use AppBundle\Exception\MaxRemainingTilesLimitException;

/**
 * Description of Grid
 *
 * @author haclong
 */
class Grid implements InitInterface, ResetInterface, ReloadInterface {
    protected $size ;
    protected $tiles = array() ;
    protected $remainingTiles = -1 ;
    
    public function init($size)
    {
        $gridSize = (int) $size ;
//        try {
            $this->validateGridSize($gridSize) ;
            $this->size = $gridSize ;
            $this->remainingTiles = $this->sizeAuCarre($gridSize) ;
//        } catch (InvalidGridSizeException $ex) {
//        }
    }
    public function reload(Grid $grid = null)
    {
        $this->remainingTiles = $this->sizeAuCarre($this->size) ;
    }
    public function reset()
    {
        $this->remainingTiles = -1 ;
        $this->tiles = [] ;
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

    public function getRemainingTiles()
    {
        return $this->remainingTiles ;
    }
    public function decreaseRemainingTiles()
    {
        $this->remainingTiles -= 1 ;
    }
    public function increaseRemainingTiles()
    {
        if($this->remainingTiles == $this->sizeAuCarre($this->size))
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
    
    protected function sizeAuCarre($size)
    {
        return $size * $size ;
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
