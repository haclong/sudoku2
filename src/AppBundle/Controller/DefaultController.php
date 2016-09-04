<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event\GridSize;
use AppBundle\Entity\Grid;
use AppBundle\Event\ChooseGridEvent;
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
        $session->clear() ;

        $grid = new Grid() ;
        $session->set('grid', $grid) ;
        $array = GridMapper::toArray($session->get('grid')) ;

        $this->debugSession("DefaultController::indexAction") ;
        
        return $this->render(
                'sudoku/index.html.twig',
                $array) ;
    }

    /**
     * @Route("/{size}", name="grid")
     */
    public function gridAction(Request $request, $size=null)
    {
        $gridSize = new GridSize($size) ;
        
        $event = new ChooseGridEvent($gridSize) ;
        $this->get('event_dispatcher')->dispatch('grid.choose', $event) ;

        $session = $this->get('session') ;
        $grid = $session->get('grid') ;
        
        $this->debugSession("DefaultController::gridAction") ;
        $array = GridMapper::toArray($grid) ;
        
        return $this->render(
                'sudoku/grid.html.twig',
                $array) ;
    }
    
    protected function debugSession($mark)
    {
        $session = $this->get('session') ;
        $logger = $this->get('logger') ;
        $grid = $session->get('grid') ;
        $array = [
            "size" => $grid->getSize(),
            "solved" => $grid->isSolved(),
            "remain" => $grid->getRemainingTiles(),
            "tiles" => json_encode($grid->getTiles())
        ] ;
        $logger->debug($mark, $array) ;
    }
}