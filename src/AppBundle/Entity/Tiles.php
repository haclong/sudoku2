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
    protected $size ;

    public function __construct(Tileset $tileset)
    {
        $this->tileset = $tileset ;
    }
    
    public function init($size)
    {
        $this->setTileset($size) ;
        $this->setSize($size) ;
        return $this ;
    }
    public function reset() 
    {
        $this->resetTileset() ;
        $this->resetSize() ;
        return $this ;
    }
    public function reload(Grid $grid)
    {
        $this->reloadTileset($grid->getSize()) ;
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
    public function set($row, $col, $value)
    {
        $this->tileset->offsetSet($row.'.'.$col, $value) ;
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
    protected function setSize($size)
    {
        $this->size = $size ;
        return $this ;
    }
    
    protected function resetSize()
    {
        $this->size = null ;
    }
    protected function resetTileset()
    {
        return $this->tileset->exchangeArray(array()) ;
    }
    protected function reloadTileset($grid)
    {
        $this->setTileset($grid) ;
    }
}