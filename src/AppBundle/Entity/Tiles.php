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

    public function __construct(Tileset $tileset)
    {
        $this->tileset = $tileset ;
    }
    
    public function init($size)
    {
        $this->setTileset($size) ;
        $this->size = $size ;
        return $this ;
    }
    public function reset() 
    {
        $this->tileset->exchangeArray(array()) ;
        $this->size = null ;
        return $this ;
    }
    public function reload(Grid $grid)
    {
        $this->setTileset($grid->getSize()) ;
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
}