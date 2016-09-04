<?php

namespace AppBundle\Entity\Event;

/**
 * Description of TilesLoaded
 *
 * @author haclong
 */
class TilesLoaded {
    protected $size ;
    protected $tiles = array() ;
    
    public function __construct($size, $tiles) {
        $this->size = $size;
        $this->tiles = $tiles;
    }

    public function getSize() {
        return $this->size;
    }

    public function getTiles() {
        return $this->tiles;
    }
}
