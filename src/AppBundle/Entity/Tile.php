<?php

namespace AppBundle\Entity;

use AppBundle\Event\DeduceTileEvent;
use AppBundle\Event\SetTileEvent;
use AppBundle\Exception\AlreadyDiscardedException;
use AppBundle\Exception\ImpossibleToDiscardException;
use AppBundle\Exception\InvalidFigureCountException;
use AppBundle\Exception\InvalidFigureException;
use AppBundle\Utils\RegionGetter;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
     * id composé de {row index}.{col index}
     * @var string
     */
    protected $id ;
    
    /**
     * tableau avec tous les index possible, les index éliminés et l'index final
     * @var array
     */
    protected $figures = array() ;
    
    /**
     * le gestionnaire d'événement
     * nécessaire parce qu'on déclenche un événement chaque fois 
     * que la case est trouvée
     * @var EventDispatcherInterface
     */
    protected $dispatcher ;
    
    /**
     * flag pour savoir si la case est résolue (on a trouvé le chiffre)
     * ou pas
     * @var bool
     */
    protected $solved = false ;
    
    /**
     * événement à déclencher quand une case a été trouvée
     * @var TileSetEvent
     */
    protected $setTileEvent ;
    
    /**
     * événement à déclencher quand il ne reste qu'une possibilité
     * @var DeduceTileEvent
     */
    protected $deduceTileEvent ;
    
    /**
     * 
     * @param EventDispatcherInterface $eventDispatcher
     * @param SetTileEvent $setTileEvent
     * @param deduceTileEvent $deduceTileEvent
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, 
                                SetTileEvent $setTileEvent,
                                DeduceTileEvent $deduceTileEvent)
    {
        $this->dispatcher = $eventDispatcher ;
        $this->setTileEvent = $setTileEvent ;
        $this->deduceTileEvent = $deduceTileEvent ;
    }
            
    public function initialize($row, $col, $size) {
        $this->size = $size ; 
        $this->row = $row;
        $this->col = $col;
        $this->region = RegionGetter::getRegion($row, $col, $size) ;
        $this->id = $row.'.'.$col ;
        $this->solved = false ;
        $this->resetFigures() ;
    }
    
    protected function resetFigures() {
        $this->figures = array() ;
        for($i=0; $i<$this->size ; $i++) 
        {
            $this->figures['possibilities'][$i] = $i ;
        }
    }
    
    protected function checkFiguresCount() {
        $i = 0 ;
        
        $i += count($this->getPossibilitiesFigure()) ;
        
        $i += count($this->getDiscardedFigure()) ;
        
        if(!$i == $this->size) {
            throw new InvalidFigureCountException() ;
        }
    }
    
    protected function checkOnePossibilityLast() {
        if(count($this->getPossibilitiesFigure()) == 1)
        {
            $this->deduceTileEvent->getTile()->set($this->row, $this->col, $this->region, current($this->figures['possibilities'])) ;
            $this->dispatcher->dispatch('tile.lastPossibility', $this->deduceTileEvent) ;
        }
    }
    
    protected function checkTile() {
        $this->checkFiguresCount() ;
        $this->checkOnePossibilityLast() ;
    }
    
    public function discard($figure) {
        if($figure >= $this->size) {
            throw new InvalidFigureException() ;
        }

        if(isset($this->figures['definitive']) && $this->figures['definitive'] == $figure)
        {
            throw new ImpossibleToDiscardException() ;
        }
        
        if(in_array($figure, $this->getPossibilitiesFigure())) 
        {
            unset($this->figures['possibilities'][$figure]) ;
            $this->figures['discarded'][$figure] = $figure ;
        }
        
        $this->checkTile() ;
//
//        if($this->isOnePossibilityLast()) {
//            $this->set(current($this->figures['possibilities'])) ;
//        }
    }
    
    public function set($figure) {
        if(in_array($figure, $this->getDiscardedFigure())) {
            throw new AlreadyDiscardedException() ;
        }
        $this->figures['definitive'] = $figure ;
        unset($this->figures['possibilities'][$figure]) ;

        foreach($this->getPossibilitiesFigure() as $value) 
        {
            unset($this->figures['possibilities'][$value]) ;
            $this->figures['discarded'][$value] = $value ;
        }
        $this->checkFiguresCount() ;
        $this->setTileEvent->getTile()->set($this->row, $this->col, $this->region, $figure) ;
        $this->dispatcher->dispatch('tile.set', $this->setTileEvent) ;
        $this->solved = true ;
    }
    
    public function reset() {
        $this->resetFigures() ;
        $this->solved = false ;
    }
    
    public function isSolved() {
        return $this->solved ;
    }
    
//    public function setSolved($bool) {
//        $this->solved = $bool ;
//    }
//    
    public function getDefinitiveFigure() {
        if(isset($this->figures['definitive']))
        {
            return $this->figures['definitive'] ;
        }
        return false ;
    }
    
    public function getSize() {
        return $this->size;
    }

//    public function getFigures() {
//        return $this->figures;
//    }
//    
    public function getPossibilitiesFigure() {
        if(!isset($this->figures['possibilities']))
        {
            $this->figures['possibilities'] = array() ;
        }
        return $this->figures['possibilities'] ;
    }

    public function getDiscardedFigure() {
        if(!isset($this->figures['discarded']))
        {
            $this->figures['discarded'] = array() ;
        }
        return $this->figures['discarded'] ;
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
    
    public function getId() {
        return $this->id ;
    }
}
