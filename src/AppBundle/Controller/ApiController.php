<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * Description of ApiController
 *
 * @author haclong
 */
class ApiController extends Controller
{
//    /**
//     * 
//     * @Route("/api/getGrid", name="getGrid")
//     */
    public function getGridAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $size = (int) $request->get('size') ;
            if($size == 4) {
                $file = __DIR__ . "/../../../datas/4/1/1.php" ;
            } elseif ($size == 9) {
                $file = __DIR__ . "/../../../datas/9/1/facile_0.php" ;
            }
            
            $array = include($file) ;
//            $grid = new Grid($size) ;
//            $grid->setTiles($array) ;
//            $getGrid = new GetGridEvent($grid) ;
//            $this->get('event_dispatcher')->dispatch('grid.get', $getGrid) ;
            //$response = $this->get('jsonMapper')->gridToJson($grid) ;
            $response = array('size' => $size, 'sqrt' => sqrt($size), 'msg' => '', 'post' => array()) ;
            return new JsonResponse($response) ;
        } else {
            return $this->render(
                    'sudoku/error.html.twig',
                    array('msg' => 'No XHR')
                    ) ;
        }
    }
}
