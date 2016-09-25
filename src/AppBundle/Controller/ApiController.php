<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event\TilesLoaded;
use AppBundle\Event\LoadGameEvent;
use AppBundle\Event\ReloadGameEvent;
use AppBundle\Event\ResetGameEvent;
use AppBundle\Utils\JsonMapper;
use AppBundle\Utils\SudokuFileMapper;
use AppBundle\Utils\TilesMapper;
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
            // récupération des objets dans le container
            $sessionMarker = $this->get('sessionMarker') ;
            $session = $this->get('sudokuSession') ;

            // récupération des paramètres dans la requete
            $gridSize = $request->get('size') ;

            // on choisit une grille à afficher
            // les grilles sont stockées dans datas/
            $loadedTiles = $this->pickAGrid($gridSize) ;
            
            // on charge la grille dans l'objet TilesLoaded
            $loadedGrid = new TilesLoaded($gridSize, $loadedTiles) ;

            // on déclenche l'événement avec la grille à résoudre
            $event = new LoadGameEvent($loadedGrid) ;
            $this->get('event_dispatcher')->dispatch('game.load', $event) ;

            $response['grid'] = TilesMapper::toArray($session->getTiles(), $session->getValues()) ;
            $sessionMarker->logSession("ApiControllerTest::loadGrid") ;
            
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
            // récupération des objets dans le container
            $sessionMarker = $this->get('sessionMarker') ;
            $session = $this->get('sudokuSession') ;
            
            // on crée l(événement reload pour réinitialiser toutes les sessions
            $event = new ReloadGameEvent($session->getGrid()) ;
            $this->get('event_dispatcher')->dispatch('game.reload', $event) ;

            // on récupère l'objet Grid qui est en session (qui a été reloadé)
            // on prépare le format de la grille à renvoyer en json
            $response['grid'] = TilesMapper::toArray($session->getTiles(), $session->getValues()) ;
            $sessionMarker->logSession("ApiController::reloadGrid") ;
        
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
            // récupération des objets dans le container
            $sessionMarker = $this->get('sessionMarker') ;
            $session = $this->get('sudokuSession') ;
            // on crée l'événement reload pour réinitialiser toutes les sessions
            $event = new ResetGameEvent() ;
            $this->get('event_dispatcher')->dispatch('game.reset', $event) ;

            // on récupère l'objet Grid qui est en session
            // on prépare le format de la grille à renvoyer en json
            $response['grid'] = TilesMapper::toArray($session->getTiles(), $session->getValues()) ;
            $sessionMarker->logSession("ApiController::resetGrid") ;
        
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
            $sessionMarker = $this->get('sessionMarker') ;
            $session = $this->get('sudokuSession') ;
            $sudokuJson = $request->getContent() ;
            $responseArray = JsonMapper::toArray($sudokuJson) ;

            $grid = $session->getGrid() ;
            $grid->reset() ;
            $grid->init($responseArray['size']) ;
            $grid->setTiles($responseArray['tiles']) ;
        
            $filesystem = new Filesystem() ;
            $path = realpath($this->getParameter('kernel.root_dir').'/..') ;
            $string = SudokuFileMapper::mapToString($grid->getTiles()) ;
            $filesystem->dumpFile($path . '/datas/'.$grid->getSize().'/'.uniqid().'.php', $string) ;
            $session->setGrid($grid) ;
            $sessionMarker->logSession("ApiController::saveGrid") ;
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
}