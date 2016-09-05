<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event\TilesLoaded;
use AppBundle\Event\ResetGameEvent;
use AppBundle\Event\LoadGameEvent;
use AppBundle\Event\ReloadGameEvent;
use AppBundle\Utils\GridMapper;
use AppBundle\Utils\JsonMapper;
use AppBundle\Utils\SudokuFileMapper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * Description of ApiController
 *
 * @author haclong
 */
class ApiController extends Controller
{
    /**
     * 
     * @Route("/api/grid/load", name="loadGrid")
     */
    public function loadGridAction(Request $request)
    {
//        if($request->isXmlHttpRequest()) {
            $gridSize = $request->get('size') ;

            // on récupère une grille dans datas/
            $loadedTiles = $this->pickAGrid($gridSize) ;
            
            $loadedGrid = new TilesLoaded($gridSize, $loadedTiles) ;
            $this->debugSession("ApiController::loadGrid::pre") ;

//            $grid = $session->get('grid') ;
//            $grid->setTiles($aGrid) ;
////            $session->set('grid', $grid) ;
                    
            $event = new LoadGameEvent($loadedGrid) ;
            $this->get('event_dispatcher')->dispatch('game.load', $event) ;
           
            $session = $this->get('session') ;
            $grid = $session->get('grid') ;
            $response['grid'] = GridMapper::toArray($grid) ;
            $this->debugSession("ApiController::loadGrid::post") ;
            
            return new JsonResponse($response) ;
//        } else {
//            return $this->render(
//                    'sudoku/error.html.twig',
//                    array('msg' => 'No XHR')
//                    ) ;
//        }
    }

    /**
     * 
     * @Route("/api/grid/reload", name="reloadGrid")
     */
    public function reloadGridAction(Request $request)
    {
//        if($request->isXmlHttpRequest()) {
            // on crée l'événement reload pour réinitialiser toutes les sessions
            $event = new ReloadGameEvent() ;
            $this->get('event_dispatcher')->dispatch('game.reload', $event) ;

            // on récupère l'objet Grid qui est en session
            $session = $this->get('session') ;
            $grid = $session->get('grid') ;
            $response['grid'] = GridMapper::toArray($grid) ;
            $this->debugSession("ApiController::reloadGrid") ;
        
            return new JsonResponse($response) ;
//        } else {
//            return $this->render(
//                    'sudoku/error.html.twig',
//                    array('msg' => 'No XHR')
//                    ) ;
//        }
    }
    
    /**
     * 
     * @Route("/api/grid/reset", name="resetGrid")
     */
    public function resetGridAction(Request $request)
    {
//        if($request->isXmlHttpRequest()) {
            // on crée l'événement reload pour réinitialiser toutes les sessions
            $event = new ResetGameEvent() ;
            $this->get('event_dispatcher')->dispatch('game.reset', $event) ;

            // on récupère l'objet Grid qui est en session
            $session = $this->get('session') ;
            $grid = $session->get('grid') ;
            $response['grid'] = GridMapper::toArray($grid) ;
            $this->debugSession("ApiController::resetGrid") ;
        
            return new JsonResponse($response) ;
//        } else {
//            return $this->render(
//                    'sudoku/error.html.twig',
//                    array('msg' => 'No XHR')
//                    ) ;
//        }
    }

    /**
     * @Route("/api/grid/save", name="saveGrid")
     */
    public function saveGridAction(Request $request)
    {
//        if($request->isXmlHttpRequest()) {
            $sudokuJson = $request->getContent() ;
            $responseArray = JsonMapper::toArray($sudokuJson) ;

            $session = $this->get('session') ;
            $grid = $session->get('grid') ;
            $grid->reset() ;
            $grid->init($responseArray['size']) ;
            $grid->setTiles($responseArray['tiles']) ;
        
            $filesystem = new Filesystem() ;
            $path = realpath($this->getParameter('kernel.root_dir').'/..') ;
            $string = SudokuFileMapper::mapToString($grid->getTiles()) ;
            $filesystem->dumpFile($path . '/datas/'.$grid->getSize().'/'.uniqid().'.php', $string) ;
            $session->set('grid', $grid) ;
            $this->debugSession("ApiController::saveGrid") ;
            return new JsonResponse($sudokuJson) ;
//        } else {
//            return $this->render(
//                    'sudoku/error.html.twig',
//                    array('msg' =>'No XHR' )
//                    ) ;
//        }
    }

    // TODO : réussir à faire un test unitaire de getGrid sans cette méthode
    protected function pickAGrid($size)
    {
        $size = (int) $size ;
        if($size == 4) {
            $file = __DIR__ . "/../../../datas/4/1/1.php" ;
        } elseif ($size == 9) {
            $file = __DIR__ . "/../../../datas/9/1/facile_0.php" ;
        }
           
        $array = include($file) ;
        
        return $array ;
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