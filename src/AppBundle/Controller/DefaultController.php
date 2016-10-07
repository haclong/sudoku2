<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event\GridSize;
use AppBundle\Event\InitGameEvent;
use AppBundle\Event\SetGameEvent;
use AppBundle\Utils\TilesMapper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
        $sudokuEntities = $this->get('sudokuEntities') ;

        // déclencher l'événement game.set
        // on initialise le jeu (on charge des entités vides dans la session)
        $event = new SetGameEvent($sudokuEntities) ;
        $this->get('event_dispatcher')->dispatch('game.set', $event) ;
        // pour la suite du debug, voir ce qui se passe dans les différents subscriber
        
        $sessionMarker->logSession("DefaultController::indexAction") ;

        return $this->render('sudoku/index.html.twig') ;
    }

    /**
     * @Route("/{size}", name="grid")
     */
    public function gridAction(Request $request, $size=null)
    {
        $sessionMarker = $this->get('sessionMarker') ;
        $session = $this->get('sudokuSession') ;

        if(!$session->isReady()) {
            return $this->redirectToRoute('homepage');
        }

        $gridSize = new GridSize($size) ;

        // déclencher l'événement game.init
        // on vient de choisir la taille de la grille de sudoku
        $event = new InitGameEvent($gridSize) ;
        $this->get('event_dispatcher')->dispatch('game.init', $event) ;

        $sessionMarker->logSession("DefaultController::gridAction") ;
        $mappedTiles = TilesMapper::toArray($session->getTiles(), $session->getValues()) ;
        return $this->render(
                'sudoku/grid.html.twig',
                $mappedTiles) ;
//        return $this->render('sudoku/grid.html.twig', ['size' => 9]) ;
    }
}
