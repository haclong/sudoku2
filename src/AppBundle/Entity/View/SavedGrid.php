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
}
