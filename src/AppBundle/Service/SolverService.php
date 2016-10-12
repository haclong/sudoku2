<?php

namespace AppBundle\Service;

use AppBundle\Event\SetTileEvent;
use AppBundle\Persistence\TilesSession;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Description of SolverService
 *
 * @author haclong
 */
class SolverService {
    protected $dispatcher ;
    protected $event ;
    protected $session ;
    public function __construct(EventDispatcherInterface $eventDispatcher, SetTileEvent $event, TilesSession $session)
    {
        $this->dispatcher = $eventDispatcher ;
        $this->event = $event ;
        $this->session = $session ;
    }
    
    public function nextMove()
    {
        $this->tiles = $this->session->getTiles() ;
        if($this->isNextTile())
        {
            $this->setNextTile() ;
        }
    }
    
//    protected function setTiles()
//    {
//        $this->tiles = $this->session->getTiles() ;
//    }
    protected function setNextTile()
    {
        $nextTileId = $this->tiles->getFirstTileToSolve() ;
        $datas = explode(".", $nextTileId) ;
        $row = $datas[0] ;
        $col = $datas[1] ;
        $index = $this->tiles->getIndexToSet($nextTileId) ;
        
        $this->event->getTile()->set($row, $col, $index) ;
        $this->dispatcher->dispatch(SetTileEvent::NAME, $this->event) ;
    }
    protected function isNextTile()
    {
        $nextTileId = $this->tiles->getFirstTileToSolve() ;
        if(!is_null($this->tiles->getIndexToSet($nextTileId)))
        {
            return true ;
        } 
        return false ;
    }
}