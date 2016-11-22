<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event\GridSize;
use AppBundle\Entity\Event\TilesLoaded;
use AppBundle\Entity\Groups;
use AppBundle\Event\InitGameEvent;
use AppBundle\Event\LoadGameEvent;
use AppBundle\Event\ResetGameEvent;
use AppBundle\Event\SetGameEvent;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of DebugController
 *
 * @author haclong
 */
class DebugController  extends Controller {

    /**
     * @Route("/debug", name="debug")
     */
    public function indexAction(Request $request)
    {
        $file = __DIR__ . "/../../../datas/9/4/tresdifficile_0.php" ;
        $array = include($file) ;


        $service = $this->get('groupsService') ;
        // on initialise les objets en session
        $sudokuEntities = $this->get('sudokuEntities') ;
        $event = new SetGameEvent($sudokuEntities) ;
        $this->get('event_dispatcher')->dispatch(SetGameEvent::NAME, $event) ;
        
        $gridSize = new GridSize(9) ;
        $event = new InitGameEvent($gridSize) ;
        $this->get('event_dispatcher')->dispatch(InitGameEvent::NAME, $event) ;
        
        $loadedGrid = new TilesLoaded(9, $array) ;
        $event = new LoadGameEvent($loadedGrid) ;
        $this->get('event_dispatcher')->dispatch(LoadGameEvent::NAME, $event) ;
        
        $grid = $this->get('gridSession')->getGrid() ;
        $tiles = $this->get('tilesSession')->getTiles() ;
        $groups = $this->get('groupsSession')->getGroups() ;
        
        $service->set($groups, 6, 6, 5) ;
        $service->set($groups, 8, 6, 3) ;
        $service->set($groups, 2, 7, 5) ;
        $service->set($groups, 2, 5, 1) ;
        $service->set($groups, 7, 0, 8) ;
        $service->set($groups, 5, 4, 8) ;
        $service->set($groups, 2, 3, 8) ;
        $service->set($groups, 2, 4, 3) ;
        $service->set($groups, 7, 3, 7) ;
        
        // reste 40 ;
        
        // make hypothesis with nextTile
        // 0.0: 6 | 8
        $service->set($groups, 6, 0, 0, false) ;
//        $service->discard($groups, 0, 6, 2) ;
//        $service->set($groups, 8, 0, 0, false) ;
//        $service->set($groups, 4, 0, 5, false) ;
//        $service->set($groups, 5, 0, 3, false) ;
//        $service->set($groups, 6, 0, 6, false) ;
//        $service->set($groups, 3, 1, 2, false) ;
//        $service->set($groups, 6, 2, 0, false) ;
//        $service->set($groups, 1, 1, 3, false) ;
//        $service->set($groups, 3, 2, 4, false) ;
//        $service->set($groups, 0, 7, 2, false) ;
//        $service->set($groups, 4, 6, 2, false) ;
//        $service->set($groups, 5, 5, 2, false) ;
//        $service->set($groups, 7, 8, 2, false) ;
//        $service->set($groups, 8, 4, 2, false) ;
//        $service->set($groups, 1, 7, 6, false) ;
//        $service->set($groups, 4, 3, 6, false) ;
//        $service->set($groups, 3, 8, 8, false) ;
//        $service->set($groups, 4, 8, 3, false) ;
//        $service->set($groups, 3, 7, 0, false) ;
//        $service->set($groups, 3, 3, 3, false) ;
//        $service->set($groups, 1, 8, 5, false) ;
//        $service->set($groups, 5, 8, 0, false) ;
//        $service->set($groups, 1, 6, 1, false) ;
//        $service->set($groups, 1, 5, 0, false) ;
//        $service->set($groups, 7, 4, 0, false) ;
//        $service->set($groups, 0, 6, 8, false) ;
//        $service->set($groups, 0, 5, 7, false) ;
//        $service->set($groups, 1, 2, 7, false) ;
//        $service->set($groups, 6, 4, 7, false) ;
//        $service->set($groups, 0, 4, 5, false) ;
//        $service->set($groups, 8, 2, 5, false) ;
//        $service->set($groups, 0, 2, 3, false) ;
//        $service->set($groups, 5, 2, 6, false) ;
//        $service->set($groups, 8, 1, 6, false) ;
//        $service->set($groups, 4, 4, 4, false) ;
//        $service->set($groups, 3, 4, 1, false) ;
//        $service->set($groups, 0, 5, 5, false) ;
//        $service->set($groups, 6, 4, 6, false) ;
//        $service->set($groups, 3, 6, 4, false) ;
//        $service->set($groups, 3, 3, 1, false) ;
//        $service->set($groups, 0, 1, 8, false) ;
        

        $tile = $tiles->getFirstTileToSolve() ;
        var_dump($tile) ;
        
        try {
        
        // la case suivante peut être déduite, on continue
        if($tile->hasValue()) {
            echo "on déduit la case suivante" ;
            $datas = explode(".", $tile->getId()) ;
            $service->set($groups, $tile->getValue(), $datas[0], $datas[1]) ;
        // la case suivante ne peut pas être déduite et il y a eu une hypothese
        } elseif(count($grid->getUnconfirmedMoves()) != 0) {
            echo "il y a eu une hypothese non conclusive" ;
            $size = $grid->getSize() ;
            $confirmedMove = $grid->getConfirmedMoves() ;
            $unconfirmedMove = $grid->getUnconfirmedMoves() ;
            $gridSize = new GridSize($size) ;
            $event = new InitGameEvent($gridSize) ;
            $this->get('event_dispatcher')->dispatch(InitGameEvent::NAME, $event) ;
            $loadedGrid = new TilesLoaded($size, $confirmedMove) ;
            $event = new LoadGameEvent($loadedGrid) ;
            $this->get('event_dispatcher')->dispatch(LoadGameEvent::NAME, $event) ;
            
        $grid->storeHypothesis($unconfirmedMove[0]) ;
        
        $nextMove = $this->getNextMove($groups, $grid) ; 
        
        var_dump($nextMove) ;
            
            
//            current($unconfirmedMove) ;
//            $row = key($unconfirmedMove) ;
//            $col = key($unconfirmedMove[$row]) ;
//            $index = $unconfirmedMove[$row][$col] ;
//            
//            if(count($groups->getValuesByTile()[$row . '.' . $col]) > 1)
//            {
//                foreach($groups->getValuesByTile()[$row . '.' . $col] as $v)
//                {
//                    if($v != $index)
//                    {
//                        $service->set($groups, $v, $row, $col, false) ;
//                    }
//                }
//            }
//            if(count($groups->getValuesByTile()[$key]) > 1)
//            {
//                
//            }
//            $tile = $tiles->getFirstTileToSolve() ;
//            $key = $tile->getId() ;
//            $coord = explode(".", $key) ;
//            foreach($groups->getValuesByTile()[$key] as $v)
//            {
//                if($v != )
//            }
//            $index = current($groups->getValuesByTile()[$key]) ;
//            $service->set($groups, $index, $coord[0], $coord[1], false) ;
            
            
//           var_dump($groups) ;
//            die() ;
        // la case suivante ne peut pas être déduite, pas d'hypothèse en cours
        } else {
            // faire une hypothese
            echo "on fait une hypothese" ;
            $key = $tile->getId() ;
            $coord = explode(".", $key) ;
            $index = current($groups->getValuesByTile()[$key]) ;
            $service->set($groups, $index, $coord[0], $coord[1], false) ;
        }
        } catch (Exception $e) {
            echo "échec de l'hypothese" ;

            $size = $grid->getSize() ;
            $confirmedMove = $grid->getConfirmedMoves() ;
            $unconfirmedMove = $grid->getUnconfirmedMoves() ;
            $gridSize = new GridSize($size) ;
            $event = new InitGameEvent($gridSize) ;
            $this->get('event_dispatcher')->dispatch(InitGameEvent::NAME, $event) ;
            $loadedGrid = new TilesLoaded($size, $confirmedMove) ;
            $event = new LoadGameEvent($loadedGrid) ;
            $this->get('event_dispatcher')->dispatch(LoadGameEvent::NAME, $event) ;
            
            $coord = explode('.', $unconfirmedMove[0]['id']) ;
            $service->discard($groups, $unconfirmedMove[0]['index'], $coord[0], $coord[1]) ;
            
        $tile = $tiles->getFirstTileToSolve() ;
        var_dump($tile) ;
            
//            current($unconfirmedMove) ;
//            $row = key($unconfirmedMove) ;
//            $col = key($unconfirmedMove[$row]) ;
//            $index = $unconfirmedMove[$row][$col] ;
//            
//            if(count($groups->getValuesByTile()[$row . '.' . $col]) > 1)
//            {
//                foreach($groups->getValuesByTile()[$row . '.' . $col] as $v)
//                {
//                    if($v != $index)
//                    {
//                        $service->set($groups, $v, $row, $col, false) ;
//                    }
//                }
//            }

        }

//        echo "0.1::0" ;
//        $service->set($groups, 0, 0, 1) ;
//        echo "1.0::1" ;
//        $service->set($groups, 1, 1, 0) ;
//        echo "2.2::0" ;
//        $service->set($groups, 0, 2, 2) ;
//        echo "3.1::1" ;
//        $service->set($groups, 1, 3, 1) ;
//        echo "3.2::2" ;
//        $service->set($groups, 2, 3, 2) ;
        
        echo "unconfirmed moves" ;
        var_dump($grid->getUnconfirmedMoves()) ;
        echo "confimed moves" ;
        var_dump($grid->getConfirmedMoves()) ;
        echo "tiles to solve" ;
        var_dump($tiles->getTilesToSolve()) ;
        echo "values by tile" ;
        var_dump($groups->getValuesByTile()) ;
        echo "values by grid" ;
        var_dump($groups->getValuesByGrid()) ;
//        for($i = 0; $i<4; $i++)
//        {
//            var_dump($groups->getRow($i)) ;
//        }
//        var_dump($groups->getCol(0)) ;
//        var_dump($groups->getRegion(1)) ;
//        var_dump($groups->getRegion(2)) ;
//        var_dump($groups->getRegion(3)) ;
//        var_dump($groups->valuesByGroup) ;
//        var_dump($groups->getValuesByGrid()) ;
//        var_dump($groups->tilesByGroup) ;
        return $this->render('sudoku/debug.html.twig', []);
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
                    //$service->set($groups, $v, $row, $col, false) ;
                    return ['id' => $key, 'index' => $index] ;
                }
            }
        }
    }
}
