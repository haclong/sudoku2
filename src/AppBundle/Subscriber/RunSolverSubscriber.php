<?php

namespace AppBundle\Subscriber;

use AppBundle\Entity\Event\GridSize;
use AppBundle\Entity\Event\TilesLoaded;
use AppBundle\Event\InitGameEvent;
use AppBundle\Event\LoadGameEvent;
use AppBundle\Event\RunSolverEvent;
use AppBundle\Exception\AlreadySetTileException;
use AppBundle\Persistence\GridSession;
use AppBundle\Persistence\GroupsSession;
use AppBundle\Persistence\TilesSession;
use AppBundle\Service\GroupsService;
use Exception;
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
    
    public function __construct(GroupsSession $session, TilesSession $tilessession, GridSession $gridsession, GroupsService $service)
    {
        $this->groupsSession = $session ;
        $this->tilesSession = $tilessession ;
        $this->gridSession = $gridsession ;
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
        $grid = $this->gridSession->getGrid() ;
        $tile = $tiles->getFirstTileToSolve() ;
        
        try {
            // la case suivante peut être déduite, on continue
            if($tile->hasValue())
            {
                // on déduit la case suivante
                $this->setChosenTile($groups, $tile) ;
            }
            // la case suivante ne peut pas être déduite et il y a eu une hypothese non conclusive
            elseif(count($grid->getUnconfirmedMoves()) != 0) 
            {
                // il y a eu une hypothese non conclusive
                $this->makeAnotherHypothesis($grid, $groups) ;
            }
            // la case suivante ne peut pas être déduite, pas d'hypothèse en cours
            else 
            {
                // faire une hypothese
                $this->makeHypothesis($groups, $tile) ;
            }
        } catch (AlreadySetTileException $exception) {
            // échec de l'hypothese
            $this->discardHypothesis($groups, $grid, $tiles) ;
        }
        
        $this->groupsSession->setGroups($groups) ;
    }
    
    protected function setChosenTile($groups, $tile)
    {
        $datas = explode(".", $tile->getId()) ;
        $this->service->set($groups, $tile->getValue(), $datas[0], $datas[1]) ;
    }
    
    protected function makeHypothesis($groups, $tile)
    {
        $key = $tile->getId() ;
        $coord = explode(".", $key) ;
        $index = current($groups->getValuesByTile()[$key]) ;
        $this->service->set($groups, $index, $coord[0], $coord[1], false) ;
    }
    
    protected function makeAnotherHypothesis($grid, $groups)
    {
        // récupérer les données de grid avant reset des infos
        $size = $grid->getSize() ;
        $confirmedMove = $grid->getConfirmedMoves() ;
        $unconfirmedMove = $grid->getUnconfirmedMoves() ;
        
        // reset des infos
        $this->service->resetGame($size) ;

        //rechargement des numéros confirmés
        $this->service->reloadGame($size, $confirmedMove) ;
        
        $grid->storeHypothesis($unconfirmedMove[0]) ;
        
        $nextMove = $this->getNextMove($groups, $grid) ; 

        $datas = explode('.', $nextMove['id']) ;
        
        $this->service->set($groups, $nextMove['index'], $datas[0], $datas[1], false) ;    
    }
    
    protected function discardHypothesis($groups, $grid, $tiles)
    {
        // récupérer les données de grid avant reset des infos
        $size = $grid->getSize() ;
        $confirmedMove = $grid->getConfirmedMoves() ;
        $unconfirmedMove = $grid->getUnconfirmedMoves() ;
        
        // reset des infos
        $this->service->resetGame($size) ;

        //rechargement des numéros confirmés
        $this->service->reloadGame($size, $confirmedMove) ;
        
        $hypothesis = $unconfirmedMove[0] ;
        $datas = explode('.', $hypothesis['id']) ;
        
        $this->service->discard($groups, $hypothesis['index'], $datas[0], $datas[1]) ;
        
        $tile = $tiles->getFirstTileToSolve() ;
        
        if($tile->hasValue())
        {
            $datas = explode(".", $tile->getId()) ;
            $this->service->set($groups, $tile->getValue(), $datas[0], $datas[1]) ;
        }
        else 
        {
            $nextMove = $this->getNextMove($groups, $grid) ; 
            $datas = explode('.', $nextMove['id']) ;
            $this->service->set($groups, $nextMove['index'], $datas[0], $datas[1], false) ;    
        }
    }

    protected function hasBeenTried($grid, $id, $index)
    {
        $hypothesis = $grid->getHypothesis() ;
        foreach($hypothesis as $tile)
        {
            if($tile['id'] == $id && $tile['index'] == $index)
            {
                return true ;
            }
        }
        return false ;
    }

    protected function getNextMove($groups, $grid)
    {
        foreach($groups->getValuesByTile() as $key => $indexes)
        {
            foreach($indexes as $index)
            {
                if(!$this->hasBeenTried($grid, $key, $index))
                {
                    return ['id' => $key, 'index' => $index] ;
                }
            }
        }
    }
}


