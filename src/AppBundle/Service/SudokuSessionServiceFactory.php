<?php

namespace AppBundle\Service;

/**
 * Description of SudokuSessionServiceFactory
 *
 * @author haclong
 */
class SudokuSessionServiceFactory {
    public static function create($session)
    {
        $service = new SudokuSessionService() ;
        $service->setSession($session) ;
        
        return $service ;
    }
}
