<?php

namespace AppBundle\Utils;

use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Description of SudokuSessionFactory
 *
 * @author haclong
 */
class SudokuSessionFactory {
    public static function create()
    {
        $session = new Session() ;
        $attributeBag = new AttributeBag('sudoku') ;
        $attributeBag->setName('sudoku') ;
        $session->registerBag($attributeBag) ;
        
        return $session ;
    }
}
