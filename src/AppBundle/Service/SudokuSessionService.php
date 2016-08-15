<?php

namespace AppBundle\Service;

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
//    
//    public function setGrid($value)
//    {
//        $this->setEntry('grid', $value) ;
//    }
    
    public function saveGrid($value)
    {
        $this->saveEntry('grid', $value) ;
    }
    
    public function getGrid()
    {
        return $this->getEntry('grid') ;
    }
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
}
