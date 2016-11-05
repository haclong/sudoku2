<?php

namespace AppBundle\Subscriber;

use AppBundle\Event\RunSolverEvent;
use AppBundle\Persistence\GroupsSession;
use AppBundle\Persistence\TilesSession;
use AppBundle\Service\GroupsService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Description of RunSolverSubscriber
 *
 * @author haclong
 */
class RunSolverSubscriber implements EventSubscriberInterface {
    protected $groupsSession ;
    protected $tilesSession ;
    protected $service ;
    
    public function __construct(GroupsSession $session, TilesSession $tilessession, GroupsService $service)
    {
        $this->groupsSession = $session ;
        $this->tilesSession = $tilessession ;
        $this->service = $service ;
    }
    
    public static function getSubscribedEvents() {
        return array(
            RunSolverEvent::NAME => 'onRunSolver'
        ) ;
    }
    
    public function onRunSolver(RunSolverEvent $event)
    {
        $groups = $this->groupsSession->getGroups() ;
        $tiles = $this->tilesSession->getTiles() ;
        $tile = $tiles->getFirstTileToSolve() ;
        
        if($tile->hasValue())
        {
            $datas = explode(".", $tile->getId()) ;
            $this->service->set($groups, $tile->getValue(), $datas[0], $datas[1]) ;
        }
        
        $this->groupsSession->setGroups($groups) ;
    }
}
