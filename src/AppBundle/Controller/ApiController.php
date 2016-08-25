<?php

namespace AppBundle\Controller;

use AppBundle\Event\GetGridEvent;
use AppBundle\Event\ResetGridEvent;
use AppBundle\Utils\GridMapper;
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
            $aGrid = $this->pickAGrid($gridSize) ;

            // on crée l'objet Grid et on enregistre la grille qu'on va charger
            $grid = $this->get('gridEntity') ;
            $grid->init($gridSize) ;
            $grid->setTiles($aGrid) ;
            
            // la création de la grille génère un événement grid.get
            $event = new GetGridEvent($grid) ;
            $this->get('event_dispatcher')->dispatch('grid.get', $event) ;
            $response['getGrid'] = GridMapper::toArray($grid) ;
//            $response = array('size' => $size) ;
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
            $session = $this->get('sudokuSessionService') ;
            var_dump($session->getSession()); die() ;
            $grid = $session->getGrid() ;
            var_dump($grid) ;
            $response['getGrid'] = GridMapper::toArray($grid) ;
        
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
            $session = $this->get('sudokuSessionService') ;
//            var_dump($session->getSession()->has('grid')) ;
            $fromSessionGrid = $session->getGrid() ;

            $filesystem = new Filesystem() ;
            $path = realpath($this->getParameter('kernel.root_dir').'/..') ;
            $sudokuJson = $request->getContent() ;
            $grid = GridMapper::fromJson($sudokuJson, $fromSessionGrid) ;
            $string = SudokuFileMapper::mapToString($grid->getSafeTiles()) ;
            $filesystem->dumpFile($path . '/datas/'.$grid->getSize().'/'.uniqid().'.php', $string) ;
            
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
        if($size == 'test') {
            $array = array() ;
            $array[0][0] = 2 ;
            $array[2][5] = 8 ;
            $array[5][3] = 5 ;
        } else {
            $size = (int) $size ;
            if($size == 4) {
                $file = __DIR__ . "/../../../datas/4/1/1.php" ;
            } elseif ($size == 9) {
                $file = __DIR__ . "/../../../datas/9/1/facile_0.php" ;
            }
            
            $array = include($file) ;
        }
        
        return $array ;
    }
}
