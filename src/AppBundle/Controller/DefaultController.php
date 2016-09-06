<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event\GridSize;
use AppBundle\Entity\Grid;
use AppBundle\Entity\Values;
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

        $grid = new Grid() ;
        $values = new Values() ;
        $session->set('grid', $grid) ;
        $session->set('values', $values) ;
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
