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
