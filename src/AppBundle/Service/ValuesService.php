<?php

namespace AppBundle\Service;

use AppBundle\Utils\SudokuSession;

/**
 * Description of ValuesService
 *
 * @author haclong
 */
class ValuesService {
    protected $session ;
    public function __construct(SudokuSession $session)
    {
        $this->session = $session ;
    }
    
    public function getValues()
    {
        return $this->session->getValues() ;
    }
    
    
}
