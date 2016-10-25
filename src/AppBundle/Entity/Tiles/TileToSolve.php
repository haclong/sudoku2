<?php

namespace AppBundle\Entity\Tiles;

/**
 * Description of TileToSolve
 *
 * @author haclong
 */
class TileToSolve {
    protected $id ;
    protected $value ;

    public function getId() {
        return $this->id;
    }

    public function getValue() {
        return $this->value;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setValue($value) {
        $this->value = $value;
    }
    
    public function __toString()
    {
        return $this->getId() ;
    }
}
