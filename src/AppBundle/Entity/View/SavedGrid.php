<?php

namespace AppBundle\Entity\View;

/**
 * Description of SavedGrid
 *
 * @author haclong
 */
class SavedGrid {
    protected $size ;
    protected $tiles = array() ;

    public function getSize() {
        return $this->size;
    }

    public function getTiles() {
        return $this->tiles;
    }

    public function setSize($size) {
        $this->size = $size;
    }

    public function setTiles($tiles) {
        $this->tiles = $tiles;
    }
    
    public function getSafeTiles() {
        $tiles = array() ;
        foreach($this->tiles as $row => $cols)
        {
            foreach($cols as $col => $value)
            {
                if(!empty($value))
                {
                    $tiles[$row][$col] = $value ;
                }
            }
        }
        return $tiles ;
    }
}
