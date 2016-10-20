<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Event\TileLastPossibility;
use AppBundle\Entity\Tiles\Tileset;

/**
 * Description of Tiles
 *
 * @author haclong
 */
class Tiles implements InitInterface, ResetInterface, ReloadInterface {
    protected $tileset ;
    protected $size ;
    protected $tilesToSolve ;
    protected $singleValues ;

    public function __construct(Tileset $tileset)
    {
        $this->tileset = $tileset ;
        $this->tilesToSolve = [] ;
        $this->singleValues = [] ;
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
        $this->singleValues = [] ;
        $this->size = null ;
        return $this ;
    }
    public function reload(Grid $grid)
    {
        $this->setTileset($grid->getSize()) ;
        $this->setTilesToSolve($this->getTileset()) ;
        $this->singleValues = [] ;
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
        unset($this->singleValues[$row .'.'.$col]) ;
    }
    public function priorizeTileToSolve(TileLastPossibility $deduceTile)
    {
        $tileId = $deduceTile->getRow() .'.'. $deduceTile->getCol() ;
        $this->removeTileToSolve($tileId) ;
        array_unshift($this->tilesToSolve, $tileId) ;
        $this->singleValues[$tileId] = $deduceTile->getValue() ;
    }
    public function getFirstTileToSolve()
    {
        reset($this->tilesToSolve) ;
        return current($this->tilesToSolve) ;
    }
    public function getIndexToSet($tileId)
    {
        if(array_key_exists($tileId, $this->singleValues))
        {
            return $this->singleValues[$tileId] ;
        }
        return null ;
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
//        foreach($this->tilesToSolve as $key => $tile)
//        {
//            if($tile == $id)
//            {
//                //unset($)
//            }
//        }
        $this->tilesToSolve = array_flip($this->tilesToSolve) ;
        unset($this->tilesToSolve[$id]) ;
        $this->tilesToSolve = array_flip($this->tilesToSolve) ;
    }
}