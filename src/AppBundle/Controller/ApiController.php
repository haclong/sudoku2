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
    /**
     * 
     * @Route("/api/getGrid", name="getGrid")
     */
    public function getGridAction(Request $request)
    {
//        if($request->isXmlHttpRequest()) {
            $aGrid = $this->pickAGrid($request->get('size')) ;
//            $grid = new Grid($size) ;
//            $grid->setTiles($array) ;
//            $getGrid = new GetGridEvent($grid) ;
//            $this->get('event_dispatcher')->dispatch('grid.get', $getGrid) ;
            //$response = $this->get('jsonMapper')->gridToJson($grid) ;
            $response['getGrid'] = array('tiles' => $aGrid) ;
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
     * @Route("/api/saveGrid", name="saveGrid")
     */
    public function saveGridAction(Request $request)
    {
//        if($request->isXmlHttpRequest()) {
//            $grid = $request->get('grid') ;
//            $grid = '{"grid":[{"id":"t.0.0","value":"1"},{"id":"t.0.1","value":"2"},{"id":"t.0.2","value":"3"},{"id":"t.0.3","value":"4"},{"id":"t.1.0","value":""},{"id":"t.1.1","value":""},{"id":"t.1.2","value":""},{"id":"t.1.3","value":""},{"id":"t.2.0","value":""},{"id":"t.2.1","value":""},{"id":"t.2.2","value":""},{"id":"t.2.3","value":""},{"id":"t.3.0","value":""},{"id":"t.3.1","value":""},{"id":"t.3.2","value":""},{"id":"t.3.3","value":""}]}' ; 
//$grid = '{"grid":{"size":9,"tiles":[{"id":"t.0.0","value":""},{"id":"t.0.1","value":""},{"id":"t.0.2","value":"2"},{"id":"t.0.3","value":""},{"id":"t.0.4","value":""},{"id":"t.0.5","value":"9"},{"id":"t.0.6","value":"1"},{"id":"t.0.7","value":""},{"id":"t.0.8","value":"6"},{"id":"t.1.0","value":"3"},{"id":"t.1.1","value":""},{"id":"t.1.2","value":"5"},{"id":"t.1.3","value":""},{"id":"t.1.4","value":"4"},{"id":"t.1.5","value":""},{"id":"t.1.6","value":"2"},{"id":"t.1.7","value":""},{"id":"t.1.8","value":""},{"id":"t.2.0","value":""},{"id":"t.2.1","value":"7"},{"id":"t.2.2","value":"9"},{"id":"t.2.3","value":"2"},{"id":"t.2.4","value":"6"},{"id":"t.2.5","value":""},{"id":"t.2.6","value":""},{"id":"t.2.7","value":""},{"id":"t.2.8","value":""},{"id":"t.3.0","value":""},{"id":"t.3.1","value":"5"},{"id":"t.3.2","value":""},{"id":"t.3.3","value":""},{"id":"t.3.4","value":""},{"id":"t.3.5","value":""},{"id":"t.3.6","value":""},{"id":"t.3.7","value":"1"},{"id":"t.3.8","value":"9"},{"id":"t.4.0","value":""},{"id":"t.4.1","value":"2"},{"id":"t.4.2","value":"1"},{"id":"t.4.3","value":"9"},{"id":"t.4.4","value":"7"},{"id":"t.4.5","value":"5"},{"id":"t.4.6","value":"8"},{"id":"t.4.7","value":"4"},{"id":"t.4.8","value":""},{"id":"t.5.0","value":"9"},{"id":"t.5.1","value":"8"},{"id":"t.5.2","value":""},{"id":"t.5.3","value":""},{"id":"t.5.4","value":""},{"id":"t.5.5","value":""},{"id":"t.5.6","value":""},{"id":"t.5.7","value":"2"},{"id":"t.5.8","value":""},{"id":"t.6.0","value":""},{"id":"t.6.1","value":""},{"id":"t.6.2","value":""},{"id":"t.6.3","value":""},{"id":"t.6.4","value":"9"},{"id":"t.6.5","value":"1"},{"id":"t.6.6","value":"7"},{"id":"t.6.7","value":"6"},{"id":"t.6.8","value":""},{"id":"t.7.0","value":""},{"id":"t.7.1","value":""},{"id":"t.7.2","value":"4"},{"id":"t.7.3","value":""},{"id":"t.7.4","value":"5"},{"id":"t.7.5","value":""},{"id":"t.7.6","value":"3"},{"id":"t.7.7","value":""},{"id":"t.7.8","value":"1"},{"id":"t.8.0","value":"7"},{"id":"t.8.1","value":""},{"id":"t.8.2","value":"6"},{"id":"t.8.3","value":"3"},{"id":"t.8.4","value":""},{"id":"t.8.5","value":""},{"id":"t.8.6","value":"9"},{"id":"t.8.7","value":""},{"id":"t.8.8","value":""}]}}' ;
//$test = SavedGridMapper::fromJson($grid) ;
////            var_dump(json_decode($grid)) ;
            return new JsonResponse($test->getSafeTiles()) ;
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
            $array = array(
                array('id' => 't.0.0', 'value' => 2),
                array('id' => 't.2.5', 'value' => 8),
                array('id' => 't.5.3', 'value' => 5),
                );
        } else {
            $size = (int) $size ;
            if($size == 4) {
                $file = __DIR__ . "/../../../datas/4/1/1.php" ;
            } elseif ($size == 9) {
                $file = __DIR__ . "/../../../datas/9/1/facile_0.php" ;
            }
            
            $fileContent = include($file) ;

            $array = array() ;
            foreach($fileContent as $row => $cols)
            {
                foreach($cols as $col => $value)
                {
                    $array[] = array('id' => 't.' .$row.'.'.$col, 'value'=> $value) ;
                }
            }
        }
        
        return $array ;
    }
}
