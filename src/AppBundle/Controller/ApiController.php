<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event\TilesLoaded;
use AppBundle\Event\ClearGridEvent;
use AppBundle\Event\GetGridEvent;
use AppBundle\Event\ResetGridEvent;
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
     * @Route("/api/grid/get", name="getGrid")
     */
    public function getGridAction(Request $request)
    {
//        if($request->isXmlHttpRequest()) {
            $gridSize = $request->get('size') ;

            // on récupère une grille dans datas/
            $loadedTiles = $this->pickAGrid($gridSize) ;
            
            $loadedGrid = new TilesLoaded($gridSize, $loadedTiles) ;
            $this->debugSession("ApiController::getGrid::pre") ;

//            $grid = $session->get('grid') ;
//            $grid->setTiles($aGrid) ;
////            $session->set('grid', $grid) ;
                    
            $event = new GetGridEvent($loadedGrid) ;
            $this->get('event_dispatcher')->dispatch('grid.get', $event) ;
           
            $session = $this->get('session') ;
            $grid = $session->get('grid') ;
            $response['getGrid'] = GridMapper::toArray($grid) ;
            $this->debugSession("ApiController::getGrid::post") ;
            
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
            $event = new ResetGridEvent() ;
            $this->get('event_dispatcher')->dispatch('grid.reset', $event) ;

            // on récupère l'objet Grid qui est en session
            $session = $this->get('session') ;
            $grid = $session->get('grid') ;
            $response['getGrid'] = GridMapper::toArray($grid) ;
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
     * 
     * @Route("/api/grid/clear", name="clearGrid")
     */
    public function clearGridAction(Request $request)
    {
//        if($request->isXmlHttpRequest()) {
            // on crée l'événement reload pour réinitialiser toutes les sessions
            $event = new ClearGridEvent() ;
            $this->get('event_dispatcher')->dispatch('grid.clear', $event) ;

            // on récupère l'objet Grid qui est en session
            $session = $this->get('session') ;
            $grid = $session->get('grid') ;
            $response['getGrid'] = GridMapper::toArray($grid) ;
            $this->debugSession("ApiController::clearGrid") ;
        
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
            $session = $this->get('session') ;
            $grid = $session->get('grid') ;
            $grid = JsonMapper::toGrid($sudokuJson, $grid) ;
        
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




//
///**
// * Description of ApiController
// *
// * @author haclong
// */
//class ApiController extends Controller
//{
//    /**
//     * 
//     * @Route("/api/grid/get", name="getGrid")
//     */
//    public function getGridAction(Request $request)
//    {
////        if($request->isXmlHttpRequest()) {
//            $gridSize = $request->get('size') ;
//
//            // on récupère une grille dans datas/
//            $aGrid = $this->pickAGrid($gridSize) ;
//            $session = $this->get('session') ;
//            $grid = $session->get('grid') ;
//            $grid->setTiles($aGrid) ;
////            $session->set('grid', $grid) ;
//
////            // on crée l'objet Grid et on enregistre la grille qu'on va charger
////            $session = $this->get('sudokuSessionService') ;
////            $grid = $session->getGrid() ;
////        var_dump($session->getSession()->getId()) ;
////            var_dump($grid) ;
////            $grid->setTiles($aGrid) ;
////            // la création de la grille génère un événement grid.get
////            $event = new GetGridEvent($grid) ;
////            $this->get('event_dispatcher')->dispatch('grid.get', $event) ;
////            $response['getGrid'] = GridMapper::toArray($grid) ;
//////            var_dump($session->getGrid()) ;
//////            $response = array('size' => $size) ;
//////            var_dump($session->getGrid()) ;
////            $event = new GetGridEvent($grid) ;
////            $this->get('event_dispatcher')->dispatch('grid.get', $event) ;
////        var_dump($session->getId()) ;
////        var_dump($session->get('grid')) ;
//            $response['getGrid'] = GridMapper::toArray($grid) ;
//            
////        return $this->render(
////                'sudoku/debug.html.twig',
////                $response) ;
//            return new JsonResponse($response) ;
////        } else {
////            return $this->render(
////                    'sudoku/error.html.twig',
////                    array('msg' => 'No XHR')
////                    ) ;
////        }
//    }
//
//    /**
//     * 
//     * @Route("/api/grid/reset", name="resetGrid")
//     */
//    public function resetGridAction(Request $request)
//    {
////        if($request->isXmlHttpRequest()) {
////            $session = $this->get('sudokuSessionService') ;
////          echo "before" ;
////            $gridBfr = $session->getGrid() ;
////        var_dump($session->getSession()->getId()) ;
////            var_dump($gridBfr) ;
////            $grid = $session->getGrid() ;
////            var_dump($_SESSION['sudoku']) ;
//
////            // on crée l'événement reload pour réinitialiser toutes les sessions
//            $event = new ResetGridEvent() ;
//            $this->get('event_dispatcher')->dispatch('grid.reset', $event) ;
////            
////            // on récupère l'objet Grid qui est en session
//////            $grid = $session->getGrid() ;
//////            var_dump($session->getGrid()) ;
////            echo "aftr" ;
////            $gridAft = $session->getGrid() ;
////        var_dump($session->getSession()->getId()) ;
////            var_dump($gridAft) ;
//            $session = $this->get('session') ;
//            $grid = $session->get('grid') ;
//            $response['getGrid'] = GridMapper::toArray($grid) ;
//        
//            return new JsonResponse($response) ;
////        } else {
////            return $this->render(
////                    'sudoku/error.html.twig',
////                    array('msg' => 'No XHR')
////                    ) ;
////        }
//    }
//
//    /**
//     * @Route("/api/grid/save", name="saveGrid")
//     */
//    public function saveGridAction(Request $request)
//    {
////        if($request->isXmlHttpRequest()) {
//            $session = $this->get('sudokuSessionService') ;
////            var_dump($session->getSession()->has('grid')) ;
//            $fromSessionGrid = $session->getGrid() ;
//
//            $filesystem = new Filesystem() ;
//            $path = realpath($this->getParameter('kernel.root_dir').'/..') ;
//            $sudokuJson = $request->getContent() ;
//            $grid = GridMapper::fromJson($sudokuJson, $fromSessionGrid) ;
//            $string = SudokuFileMapper::mapToString($grid->getSafeTiles()) ;
//            $filesystem->dumpFile($path . '/datas/'.$grid->getSize().'/'.uniqid().'.php', $string) ;
//            
//            return new JsonResponse($sudokuJson) ;
////        } else {
////            return $this->render(
////                    'sudoku/error.html.twig',
////                    array('msg' =>'No XHR' )
////                    ) ;
////        }
//    }
//
//    // TODO : réussir à faire un test unitaire de getGrid sans cette méthode
//    protected function pickAGrid($size)
//    {
//        $size = (int) $size ;
//        if($size == 4) {
//            $file = __DIR__ . "/../../../datas/4/1/1.php" ;
//        } elseif ($size == 9) {
//            $file = __DIR__ . "/../../../datas/9/1/facile_0.php" ;
//        }
//           
//        $array = include($file) ;
//        
//        return $array ;
//    }
//}
