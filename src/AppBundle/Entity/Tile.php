<?php

namespace AppBundle\Entity;

use AppBundle\Exception\AlreadyDiscardedException;
use AppBundle\Exception\ImpossibleToDiscardException;
use AppBundle\Utils\RegionGetter;

/**
 * Description of Tile
 *
 * @author haclong
 */
class Tile {
    /**
     * taille de la grille de sudoku
     * @var int
     */
    protected $size ;
    
    /**
     * numéro de ligne (à partir de 0) de la case
     * @var int
     */
    protected $row ;

    /**
     * numéro de colonne (à partir de 0) de la case
     * @var int
     */
    protected $col ;
    
    /**
     * numéro de région de la case
     * (à partir de 0, de gauche à droite, de haut en bas)
     * @var int
     */
    protected $region ;

    /**
     * tableau avec tous les index possibles
     * @var array
     */
    protected $maybeValues = array() ;
    
    /**
     * tableau avec tous les index écartés
     * @var array
     */
    protected $discardValues = array() ;
    
    /**
     * index final
     * @var int
     */
    protected $value = null ;
    
    public function initialize($row, $col, $size) {
        $this->size = $size ; 
        $this->row = $row;
        $this->col = $col;
        $this->resetValues() ;
        return $this ;
    }
    
    protected function resetValues() {
        $this->discardValues = array() ;
        $this->value = null ;
        $this->solved = false ;
        $this->maybeValues = array() ;

        for($i=0; $i<$this->size ; $i++) 
        {
            $this->maybeValues[$i] = $i ;
        }
    }
    
    public function discard($figure) {
        if(!is_null($this->value) && $this->value == $figure) {
            throw new ImpossibleToDiscardException() ;
        }
        
        unset($this->maybeValues[$figure]) ;
        $this->discardValues[$figure] = $figure ;
    }
    
    public function set($figure) {
        if(in_array($figure, $this->discardValues)) {
            throw new AlreadyDiscardedException() ;
        }
        $this->value = $figure ;
        unset($this->maybeValues[$figure]) ;

        foreach($this->maybeValues as $value) 
        {
            unset($this->maybeValues[$value]) ;
            $this->discardValues[$value] = $value ;
        }
        $this->solved = true ;
    }
    
    public function reset() {
        $this->resetValues() ;
    }
    
    public function isSolved() {
        return $this->solved ;
    }

    public function getMaybeValues() {
        if(!isset($this->maybeValues))
        {
            $this->maybeValues = array() ;
        }
        return $this->maybeValues ;
    }

    public function getDiscardValues() {
        if(!isset($this->discardValues))
        {
            $this->discardValues = array() ;
        }
        return $this->discardValues ;
    }
    
    public function getValue() {
        if(!is_null($this->value)) {
            return $this->value ;
        } else {
            return null ;
        }
    }

    public function getRow() {
        return $this->row;
    }

    public function getCol() {
        return $this->col;
    }
    
    public function getSize() {
        return $this->size;
    }

    public function getRegion() {
        return RegionGetter::getRegion($this->getRow(), $this->getCol(), $this->getSize()) ;
    }
    
    public function getId() {
        return $this->row . '.' . $this->col ;
    }
}
