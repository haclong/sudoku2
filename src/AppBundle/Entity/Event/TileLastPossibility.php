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
     * index de la region
     * @var int
     */
    protected $region ;
    /**
     * numéro de la case
     * @var int
     */
    protected $figure ;
    
    public function set($row, $col, $region, $figure) {
        $this->row = $row;
        $this->col = $col;
        $this->region = $region;
        $this->figure = $figure;
    }

    public function getRow() {
        return $this->row;
    }

    public function getCol() {
        return $this->col;
    }

    public function getRegion() {
        return $this->region;
    }

    public function getFigure() {
        return $this->figure;
    }
}
