<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Grid;
use AppBundle\Event\GetGridEvent;
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
            $grid = new Grid($gridSize) ;
            $grid->setTiles($aGrid) ;
            
            // la création de la grille génère un événement grid.get
            $event = new GetGridEvent($grid) ;
            $this->get('event_dispatcher')->dispatch('grid.get', $event) ;
            //$response = $this->get('jsonMapper')->gridToJson($grid) ;
            $arrayForJson = SudokuFileMapper::prepareArrayForJson($aGrid) ;
            $response['getGrid'] = array('tiles' => $arrayForJson) ;
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
     * @Route("/api/grid/reload", name="reloadGrid")
     */
    public function reloadGridAction(Request $request)
    {
//        if($request->isXmlHttpRequest()) {
            // on crée l'événement reload pour réinitialiser toutes les sessions
            $event = new ReloadGridEvent() ;
            $this->get('event_dispatcher')->dispatch('grid.reload', $event) ;
            
            // on crée l'objet Grid qui est en session
//            $grid = new Grid(9) ;
//            $aGrid = $this->pickAGrid(9) ;
//            $grid->setTiles($aGrid) ;
            
            $arrayForJson = SudokuFileMapper::prepareArrayForJson($grid->getTiles()) ;
            $response['getGrid'] = array('tiles' => $arrayForJson) ;
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
            $filesystem = new Filesystem() ;
            $path = realpath($this->getParameter('kernel.root_dir').'/..') ;
            $sudokuJson = $request->getContent() ;
            $jsonToArray = GridMapper::fromJson($sudokuJson) ;
            $string = SudokuFileMapper::mapToString($jsonToArray->getSafeTiles()) ;
            $filesystem->dumpFile($path . '/datas/'.$jsonToArray->getSize().'/'.uniqid().'.php', $string) ;
            
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
