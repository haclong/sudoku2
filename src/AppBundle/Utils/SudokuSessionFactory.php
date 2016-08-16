<?php

namespace AppBundle\Utils;

use Exception;
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
//        try {
            $attributeBag = new AttributeBag('sudoku') ;
            $attributeBag->setName('sudoku') ;
            $session->registerBag($attributeBag) ;
//        } catch (Exception $ex) {
//            $session->getBag('sudoku') ;
//        }
        
        return $session ;
    }
}
