<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event\TileSet;
use AppBundle\Entity\Event\TilesLoaded;
use AppBundle\Event\LoadGameEvent;
use AppBundle\Event\ReloadGameEvent;
use AppBundle\Event\ResetGameEvent;
use AppBundle\Event\RunSolverEvent;
use AppBundle\Event\SetTileEvent;
use AppBundle\Exception\AlreadySetTileException;
use AppBundle\Utils\JsonMapper;
use AppBundle\Utils\SudokuFileMapper;
use AppBundle\Utils\TilesMapper;
use InvalidArgumentException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
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

            if(!$session->isReady()) {
                return $this->redirectToRoute('homepage');
            }

            // récupération des paramètres dans la requete
            $gridSize = $request->get('size') ;

            // on choisit une grille à afficher
            // les grilles sont stockées dans datas/
            $size = (int) $gridSize ;
//        if($size == 4) {
//            $file = __DIR__ . "/../../../datas/4/1/1.php" ;
//        } elseif ($size == 9) {
//            $file = __DIR__ . "/../../../datas/9/1/facile_0.php" ;
//        }
            $finder = new Finder() ;
            
            try {
                $finder->files()->in(__DIR__ . "/../../../datas/" . $size) ;

                $fileList = iterator_to_array($finder) ;
                $file = array_rand($fileList) ;

                $loadedTiles = include($file) ;
            } catch (InvalidArgumentException $e) {
                $response['error'] = ['id' => 500] ;
                $loadedTiles = [] ;
            }

            // on charge la grille dans l'objet TilesLoaded
            $loadedGrid = new TilesLoaded($gridSize, $loadedTiles) ;

            // on déclenche l'événement avec la grille à résoudre
            $event = new LoadGameEvent($loadedGrid) ;
            $this->get('event_dispatcher')->dispatch(LoadGameEvent::NAME, $event) ;

            $response['grid'] = TilesMapper::toArray($session->getTiles(), $session->getValues()) ;
            $sessionMarker->logSession("ApiController::loadGrid") ;
            
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
     * @Route("/api/tile/set", name="setTile")
     */
    public function setTileAction(Request $request)
    {
//        if($request->isXmlHttpRequest()) {
            // récupération des objets dans le container
            $sessionMarker = $this->get('sessionMarker') ;
            $session = $this->get('sudokuSession') ;
            $gridSession = $this->get('gridSession') ;

            if(!$session->isReady()) {
                return $this->redirectToRoute('homepage');
            }

//            $json = '{"tile":{"id":"t.1.3","value":"2"}}' ;
//            $sudokuJson = json_decode($json) ;
            $sudokuJson = json_decode($request->getContent()) ;
            $explodedArray = explode('.', $sudokuJson->tile->id) ;
            $row = $explodedArray[1] ;
            $col = $explodedArray[2] ;
            $val = $sudokuJson->tile->value ;

            // on charge la grille dans l'objet TilesSet
            $setTile = new TileSet() ;
            $setTile->set($row, $col, $val) ;
//var_dump($setTile) ;
            try {
            // on déclenche l'événement avec la grille à résoudre
            $event = new SetTileEvent($setTile) ;
            $this->get('event_dispatcher')->dispatch(SetTileEvent::NAME, $event) ;
            } catch (AlreadySetTileException $e) {
                $response['error'] = ["id" => "t.".$row.".".$col] ;
            }
            
            if($gridSession->getGrid()->isSolved())
            {
                $response['solved'] = ["status" => 1] ;
            }
            
            $response['grid'] = TilesMapper::toArray($session->getTiles(), $session->getValues()) ;
            $sessionMarker->logSession("ApiController::setTile." .$row. "." .$col. "." .$val) ;
            
            return new JsonResponse($response) ;
//        } else {
//            return $this->render(
//                    'sudoku/error.html.twig',
//                    array('msg' => 'No XHR')
//                    ) ;
//        }
    }
    
    /**
     * @Route("/api/grid/solve", name="solveGrid")
     * 
     */
    public function solveGridAction(Request $request)
    {
//        if($request->isXmlHttpRequest()) {
            // récupération des objets dans le container
            $sessionMarker = $this->get('sessionMarker') ;
            $session = $this->get('sudokuSession') ;
            $gridSession = $this->get('gridSession') ;

            if(!$session->isReady()) {
                return $this->redirectToRoute('homepage');
            }
            
            
            // on crée l'événement runSolver 
            $event = new RunSolverEvent() ;
            $this->get('event_dispatcher')->dispatch(RunSolverEvent::NAME, $event) ;

            if($gridSession->getGrid()->isSolved())
            {
                $response['solved'] = ["status" => 1] ;
            }
            // on récupère l'objet Grid qui est en session (qui a été reloadé)
            // on prépare le format de la grille à renvoyer en json
            $response['grid'] = TilesMapper::toArray($session->getTiles(), $session->getValues()) ;
            $sessionMarker->logSession("ApiController::solveGrid") ;
        
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

            if(!$session->isReady()) {
                return $this->redirectToRoute('homepage');
            }
            
            // on crée l(événement reload pour réinitialiser toutes les sessions
            $event = new ReloadGameEvent($session->getGrid()) ;
            $this->get('event_dispatcher')->dispatch(ReloadGameEvent::NAME, $event) ;

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

            if(!$session->isReady()) {
                return $this->redirectToRoute('homepage');
            }
            // on crée l'événement reload pour réinitialiser toutes les sessions
            $event = new ResetGameEvent() ;
            $this->get('event_dispatcher')->dispatch(ResetGameEvent::NAME, $event) ;

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
}