<?php

namespace AppBundle\Persistence;

use AppBundle\Entity\Groups;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Description of GroupsSession
 *
 * @author haclong
 */
class GroupsSession implements IsReadyInterface {
    protected $session ;
    
    public function __construct(Session $session) {
        $this->session = $session;
    }

    public function getGroups()
    {
        return $this->session->get('groups') ;
    }
    public function setGroups(Groups $groups)
    {
        $this->session->set('groups', $groups) ;
    }
    public function isReady()
    {
        if(!$this->getGroups() instanceof Groups) {
            return false ;
        }
        return true ;
    }
}
