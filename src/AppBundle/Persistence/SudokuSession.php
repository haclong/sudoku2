<?php

namespace AppBundle\Persistence;

use AppBundle\Entity\Persistence\SessionContent;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Description of SudokuSession
 *
 * @author haclong
 */
class SudokuSession {
    protected $session ;
    
    public function __construct(Session $session, SessionContent $content) {
        $this->session = $session;
        $this->content = $content ;
    }
    
    public function clear()
    {
        $this->session->clear() ;
    }
    
    public function isReady()
    {
        foreach($this->content as $content)
        {
            if(!$content->isReady())
            {
                return false ;
            }
        }
        return true; 
    }
    
    public function getGrid()
    {
        if($this->session->has('grid'))
        {
            return $this->session->get('grid') ;
        }
        return null ;
    }
    
    public function getValues()
    {
        if($this->session->has('values'))
        {
            return $this->session->get('values') ;
        }
        return null ;
    }
    
    public function getTiles()
    {
        if($this->session->has('tiles'))
        {
            return $this->session->get('tiles') ;
        }
        return null ;
    }
}