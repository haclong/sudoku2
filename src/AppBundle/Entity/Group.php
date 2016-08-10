<?php

namespace AppBundle\Entity;

use AppBundle\Exception\InvalidCountOfTilesInGroupException;

/**
 * Description of Group
 *
 * @author haclong
 */
class Group {
    /**
     * tableau des id des cases d'un groupe
     * @var array
     */
    protected $tiles = array() ;
    
    /**
     * flag pour savoir si le groupe est résolu ou pas
     * @var bool
     */
    protected $solved = false ;
    
    /**
     * index du groupe (en fonction du type)
     * @var int
     */
    protected $index ;
    
    /**
     * type du groupe (col, region ou row)
     * @var string
     */
    protected $type ;
    
    /**
     * taille de la grille de sudoku
     * @var int
     */
    protected $gridSize ;

    public function set($type, $index, $gridSize)
    {
        $this->index = $index ;
        $this->type = $type ;
        $this->gridSize = $gridSize ;
        $this->solved = false ;
    }

    public function addTile($tileId)
    {
        if(!in_array($tileId, $this->tiles)) {
            if(count($this->tiles == $this->gridSize)) {
                throw new InvalidCountOfTilesInGroupException ;
            }
            $this->tiles[] = $tileId ;
        }
    }
    
    public function reset()
    {
        $this->tiles = array() ;
        $this->solved = false ;
    }
    
    public function solve($bool)
    {
        $this->solved = $bool ;
    }
    
    public function isSolved()
    {
        return $this->solved ;
    }
    
    public function getId()
    {
        return $this->type .'.'.$this->index ;
    }
    
    public function getIndex() {
        return $this->index;
    }

    public function getType() {
        return $this->type;
    }

    public function getGridSize() {
        return $this->gridSize;
    }

    public function getTiles()
    {
        return $this->tiles ;
    }
}
