<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Tiles\Tileset;

/**
 * Description of Tiles
 *
 * @author haclong
 */
class Tiles {
    protected $tileset ;
    protected $tile ;
    protected $size ;

    public function __construct(Tileset $tileset, Tile $tile)
    {
        $this->tileset = $tileset ;
        $this->tile = $tile ;
    }
    
    public function setTileset($size)
    {
        for($row=0; $row<$size; $row++)
        {
            for($col=0; $col<$size; $col++)
            {
                $tile = clone $this->tile ;
                $this->tileset->offsetSet($row.'.'.$col, $tile->initialize($row, $col, $size)) ;
            }
        }
        $this->size = $size ;
        return $this ;
    }
    
    public function getSize()
    {
        return $this->size ;
    }
    
    public function getTileset()
    {
        return $this->tileset ;
    }
    
    public function getTile($row, $col)
    {
        return $this->tileset->offsetGet($row.'.'.$col) ;
    }
    
    public function setTiles($grid)
    {
        foreach($grid as $row => $cols)
        {
            // ATTENTION : $value = $valuesEntity index
            foreach($cols as $col => $value)
            {
                $tile = $this->getTile(0, 0) ;
                $tile->set($value) ;
            }
            
        }
    }

    public function reset() 
    {
        $this->resetTileset() ;
        return $this ;
    }
    
    public function reload()
    {
        $this->reloadTileset() ;
    }
    
    protected function resetTileset()
    {
        return $this->tileset->exchangeArray(array()) ;
    }
    
    protected function reloadTileset($grid)
    {
        $this->setTiles($grid) ;
    }
}