<?php


namespace AppBundle\Service;
use AppBundle\Entity\Grid;
use AppBundle\Entity\Values;

/**
 * Description of SudokuSessionService
 *
 * @author haclong
 */
class SudokuSessionService {
    protected $sudokuSessionBag ;
    
    public function __construct($session) {
        $this->sudokuSessionBag = $session->getBag('sudoku');
    }

    public function getSession() {
        return $this->sudokuSessionBag;
    }
    
    public function saveGrid(Grid $value)
    {
        $this->saveEntry('grid', $value) ;
    }
    
    public function resetGrid()
    {
        $this->resetEntry('grid') ;
    }
    
    public function getGrid()
    {
        return $this->getEntry('grid') ;
    }
    
    public function saveValues(Values $value)
    {
        $this->saveEntry('values', $value) ;
    }
    
    public function resetValues()
    {
        $this->resetEntry('values') ;
    }
    
    public function getValues()
    {
        return $this->getEntry('values') ;
    }
//    
//    public function setTiles(Tiles $value)
//    {
//        $this->setEntry('tiles', $value) ;
//    }
    
//    public function saveTiles(Tiles $value)
//    {
//        $this->saveEntry('tiles', $value) ;
//    }
//    
//    public function resetTiles()
//    {
//        $this->resetEntry('tiles') ;
//    }
//    
//    public function getTiles()
//    {
//        return $this->getEntry('tiles') ;
//    }
//    
//    protected function setEntry($key, $value)
//    {
//        if($this->sudokuSessionBag->has($key)) {
//            return $this->sudokuSessionBag->get($key) ;
//        } else {
//            return $this->sudokuSessionBag->set($key, $value) ;
//        }
//    }
    
    protected function saveEntry($key, $value)
    {
        return $this->sudokuSessionBag->set($key, $value) ;
    }
    
    protected function getEntry($key)
    {
        return $this->sudokuSessionBag->get($key) ;
    }
    
    protected function resetEntry($key)
    {
        $entry = $this->sudokuSessionBag->get($key) ;
//        echo "sudokuSessionService : ". get_class($entry) ;
        $entry->reset() ;
    }
}
