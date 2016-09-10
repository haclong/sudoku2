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
        $session = $this->get('session') ;
        $sessionMarker = $this->get('sessionMarker') ;
        $session->clear() ;

        $grid = $this->get('gridEntity') ;
        $values = $this->get('valuesEntity') ;
        $tiles = $this->get('tilesEntity') ;
        $session->set('grid', $grid) ;
        $session->set('values', $values) ;
        $session->set('tiles', $tiles) ;
        $array = GridMapper::toArray($session->get('grid')) ;

        $sessionMarker->logSession("DefaultController::indexAction") ;
        
        return $this->render(
                'sudoku/index.html.twig',
                $array) ;
    }

    /**
     * @Route("/{size}", name="grid")
     */
    public function gridAction(Request $request, $size=null)
    {
        $sessionMarker = $this->get('sessionMarker') ;
        
        $gridSize = new GridSize($size) ;
        
        // déclencher l'événement game.choose
        // on vient de choisir la taille de la grille de sudoku
        $event = new ChooseGameEvent($gridSize) ;
        $this->get('event_dispatcher')->dispatch('game.choose', $event) ;

        $session = $this->get('session') ;
        $grid = $session->get('grid') ;
        
        $sessionMarker->logSession("DefaultController::gridAction") ;
        $array = GridMapper::toArray($grid) ;
        
        return $this->render(
                'sudoku/grid.html.twig',
                $array) ;
    }
}
