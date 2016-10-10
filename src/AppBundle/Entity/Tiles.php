<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Tiles\Tileset;

/**
 * Description of Tiles
 *
 * @author haclong
 */
class Tiles implements InitInterface, ResetInterface, ReloadInterface {
    protected $tileset ;
    protected $size ;
    public $tilesToSolve ;

    public function __construct(Tileset $tileset)
    {
        $this->tileset = $tileset ;
        $this->tilesToSolve = [] ;
    }
    
    public function init($size)
    {
        $this->setTileset($size) ;
        $this->setTilesToSolve($this->getTileset()) ;
        $this->size = $size ;
        return $this ;
    }
    public function reset() 
    {
        $this->tileset->exchangeArray(array()) ;
        $this->tilesToSolve = [] ;
        $this->size = null ;
        return $this ;
    }
    public function reload(Grid $grid)
    {
        $this->setTileset($grid->getSize()) ;
        $this->setTilesToSolve($this->getTileset()) ;
    }
    public function getSize()
    {
        return $this->size ;
    }
    public function getTileset()
    {
        return $this->tileset ;
    }
    public function getTilesToSolve()
    {
        return $this->tilesToSolve ;
    }
    public function getTile($row, $col)
    {
        return $this->tileset->offsetGet($row.'.'.$col) ;
    }
    public function set($row, $col, $value)
    {
        $this->tileset->offsetSet($row.'.'.$col, $value) ;
        $this->removeTileToSolve($row.'.'.$col) ;
    }
    public function priorizeTileToSolve($tileId)
    {
        $this->removeTileToSolve($tileId) ;
//        unset($this->tilesToSolve[$tileId]) ;
        array_unshift($this->tilesToSolve, $tileId) ;
    }
    public function getFirstTileToSolve()
    {
        reset($this->tilesToSolve) ;
        return current($this->tilesToSolve) ;
    }
    
    protected function setTileset($size)
    {
        for($row=0; $row<$size; $row++)
        {
            for($col=0; $col<$size; $col++)
            {
                $this->tileset->offsetSet($row.'.'.$col, NULL) ;
            }
        }
        return $this ;
    }
    protected function setTilesToSolve($tileset)
    {
        $this->tilesToSolve = [] ;
        foreach($tileset as $key => $index)
        {
            array_push($this->tilesToSolve, $key) ;
        }
    }
    protected function removeTileToSolve($id)
    {
        $this->tilesToSolve = array_flip($this->tilesToSolve) ;
        unset($this->tilesToSolve[$id]) ;
        $this->tilesToSolve = array_flip($this->tilesToSolve) ;
    }
}