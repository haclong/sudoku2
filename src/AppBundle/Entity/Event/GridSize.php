<?php

namespace AppBundle\Entity\Event;

/**
 * Description of GridSize
 *
 * @author haclong
 */
class GridSize {
    protected $size ;
    public function get() {
        return $this->size;
    }

    public function __construct($size) {
        $this->size = (int) $size;
    }
}
