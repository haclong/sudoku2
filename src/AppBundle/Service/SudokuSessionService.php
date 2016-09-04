<?php


namespace AppBundle\Service;
use AppBundle\Entity\Grid;
use AppBundle\Entity\Values;
use AppBundle\Entity\Tiles;


/**
 * Description of SudokuSessionService
 *
 * @author haclong
 */
class SudokuSessionService {
    protected $session ;
    protected $sessionBag ;
    
    public function setSession($session) {
        $this->session = $session ;
        $this->sessionBag = $session->getBag('sudoku');
    }

    public function getSession() {
        return $this->session;
    }
    
    public function getSessionBag() {
        return $this->sessionBag ;
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
    
    public function saveTiles(Tiles $value)
    {
        $this->saveEntry('tiles', $value) ;
    }
    
    public function resetTiles()
    {
        $this->resetEntry('tiles') ;
    }
    
    public function getTiles()
    {
        return $this->getEntry('tiles') ;
    }
    
    protected function saveEntry($key, $value)
    {
        $this->sessionBag->set($key, $value) ;
    }
    
    protected function getEntry($key)
    {
        return $this->sessionBag->get($key) ;
    }
    
    protected function resetEntry($key)
    {
        $entry = $this->sessionBag->get($key) ;
        $entry->reset() ;
    }
}
