<?php

namespace AppBundle\Entity\Event;

/**
 * Description of TileLastPossibility
 *
 * @author haclong
 */
class TileLastPossibility {
    /**
     * index de la ligne
     * @var int
     */
    protected $row ;
    /**
     * index de la colonne
     * @var int
     */
    protected $col ;
    /**
     * numéro de la case
     * @var int
     */
    protected $value ;
    
    public function set($row, $col, $value) {
        $this->row = $row;
        $this->col = $col;
        $this->value = $value;
    }

    public function getRow() {
        return $this->row;
    }

    public function getCol() {
        return $this->col;
    }

    public function getValue() {
        return $this->value;
    }
}
