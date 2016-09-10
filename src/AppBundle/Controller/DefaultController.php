<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event\GridSize;
use AppBundle\Event\ChooseGameEvent;
use AppBundle\Utils\GridMapper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Finder\Finder ;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $session = $this->get('sudokuSession') ;
        $sessionMarker = $this->get('sessionMarker') ;
        $session->clear() ;

////        $grid = $this->get('gridEntity') ;
//        $values = $this->get('valuesEntity') ;
//        $tiles = $this->get('tilesEntity') ;
////        $session->set('grid', $grid) ;
//        $session->set('values', $values) ;
//        $session->set('tiles', $tiles) ;
//        $mappedTiles = TilesMapper::toArray($session->get('tiles')) ;
        $mappedTiles = GridMapper::toArray($session->getGrid()) ;

        $sessionMarker->logSession("DefaultController::indexAction") ;
        
        return $this->render(
                'sudoku/index.html.twig',
                $mappedTiles) ;
    }

    /**
     * @Route("/{size}", name="grid")
     */
    public function gridAction(Request $request, $size=null)
    {
        $sessionMarker = $this->get('sessionMarker') ;
        $session = $this->get('sudokuSession') ;
        
        $gridSize = new GridSize($size) ;
        
        // déclencher l'événement game.choose
        // on vient de choisir la taille de la grille de sudoku
        $event = new ChooseGameEvent($gridSize) ;
        $this->get('event_dispatcher')->dispatch('game.choose', $event) ;

//        $tiles = $session->getTiles() ;
        $grid = $session->getGrid() ;
        
        $sessionMarker->logSession("DefaultController::gridAction") ;
//        $mappedTiles = TilesMapper::toArray($session->get((tiles')) ;
        $mappedTiles = GridMapper::toArray($grid) ;
        
        return $this->render(
                'sudoku/grid.html.twig',
                $mappedTiles) ;
    }
}
