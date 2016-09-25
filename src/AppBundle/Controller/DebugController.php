<?php

namespace AppBundle\Controller;

use AppBundle\Utils\RegionGetter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of DebugController
 *
 * @author haclong
 */
class DebugController  extends Controller {

    /**
     * @Route("/debug", name="debug")
     */
    public function indexAction(Request $request)
    {
        $service = $this->get('groupsService') ;
        $groups = $this->get('groupsEntity') ;
        $groups->init(9) ;
//        var_dump($groups->getCol(5)) ;
//        var_dump($groups->getRow(3)) ;
//        var_dump($groups->getRegion(RegionGetter::getRegion(3, 5, 9))) ;

        $service->set($groups, 1, 0, 2) ;
//        $service->set($groups, 8, 0, 5) ;
//        $service->set($groups, 0, 0, 6) ;
//        $service->set($groups, 5, 0, 8) ;
//
//        $service->set($groups, 2, 1, 0) ;
//        $service->set($groups, 4, 1, 2) ;
//        $service->set($groups, 3, 1, 4) ;
//        $service->set($groups, 1, 1, 6) ;
//
//        $service->set($groups, 6, 2, 1) ;
//        $service->set($groups, 8, 2, 2) ;
//        $service->set($groups, 1, 2, 3) ;
//        $service->set($groups, 5, 2, 4) ;
//
//        $service->set($groups, 4, 3, 1) ;
//        $service->set($groups, 0, 3, 7) ;
//        $service->set($groups, 8, 3, 8) ;
//
//        $service->set($groups, 1, 4, 1) ;
//        $service->set($groups, 0, 4, 2) ;
//        $service->set($groups, 8, 4, 3) ;
//        $service->set($groups, 6, 4, 4) ;
//        $service->set($groups, 4, 4, 5) ;
//        $service->set($groups, 7, 4, 6) ;
//        $service->set($groups, 3, 4, 7) ;
//
//        $service->set($groups, 8, 5, 0) ;
//        $service->set($groups, 7, 5, 1) ;
//        $service->set($groups, 1, 5, 7) ;
//
//        $service->set($groups, 8, 6, 4) ;
//        $service->set($groups, 0, 6, 5) ;
//        $service->set($groups, 6, 6, 6) ;
//        $service->set($groups, 5, 6, 7) ;
// 
//        $service->set($groups, 3, 7, 2) ;
//        $service->set($groups, 4, 7, 4) ;
//        $service->set($groups, 2, 7, 6) ;
//        $service->set($groups, 0, 7, 8) ;
// 
//        $service->set($groups, 6, 8, 0) ;
//        $service->set($groups, 5, 8, 2) ;
//        $service->set($groups, 2, 8, 3) ;
//        $service->set($groups, 8, 8, 6) ;
        
        
        
//        var_dump($groups->getCol(5)) ;
//        var_dump($groups->getRow(3)) ;
//        var_dump($groups->getRegion(RegionGetter::getRegion(3, 5, 9))) ;
        $impactedTiles = $groups->getImpactedTiles(3, 5) ;
//        var_dump($impactedTiles) ;
        
//        $this->discard($groups->getValuesByGroup(), 2, $impactedTiles) ;
        $array = [] ;
//        foreach($groups->getValuesByTile() as $tileId => $datas)
//        {
//            if((count($datas['col']) != count($datas['row'])) && (count($datas['col']) != count($datas['region'])))
//            {
//                throw new Exception() ;
//            }
//            if(count($datas['col']) == 1)
//            {
//                
//                var_dump($tileId .'-'.$datas['col'][0]) ;
//            }
//        }
//        
        foreach($groups->getValuesByGroup() as $k => $grouptype)
        {
            foreach($grouptype as $index => $group)
            {
                foreach($group as $value => $tile)
                {
                    echo $k . '.' . $index . '.' . $value ;
                    var_dump($tile) ;
                    $array[$k][$index][$value] = count($tile) ;
                }
            }
        }
//            foreach($groups->getValuesByGroup() as $type => $grouptype)
//            {
//                foreach($grouptype as $index => $group)
//                {
//                    foreach($group as $value => $figure)
//                    {
//                        foreach($figure as $key => $tileId)
//                        {
//                        $array[$tileId][$type][] = $value ;
//                        }
//                    }
//                }
//            }
        var_dump($array) ;
        return $this->render('sudoku/debug.html.twig', []);
    }
//    protected function discard(&$groups, $value, $impactedTiles)
//    {
//        // col, row, region
//        foreach($groups as $grouptype => &$group)
//        {
//            foreach($group as $index => &$values)
//            {
//                foreach($values as $key => &$tiles)
//                {
//                    if($key == $value)
//                    {
//                        echo $grouptype . "::" . $index . "::" . $key . "::";
//                        foreach($impactedTiles as $impactedTile)
//                        {
//                            $flippedTiles = array_flip($tiles) ;
//                            unset($flippedTiles[$impactedTile]) ;
//                            $tiles = array_flip($flippedTiles) ;
//                        }
//                    }
//                }
//            }
//        }
//    }
}
    

//array (size=21)
//  0 => string '0.5' (length=3)
//  1 => string '1.5' (length=3)
//  2 => string '2.5' (length=3)
//  3 => string '3.5' (length=3)
//  4 => string '4.5' (length=3)
//  5 => string '5.5' (length=3)
//  6 => string '6.5' (length=3)
//  7 => string '7.5' (length=3)
//  8 => string '8.5' (length=3)
//  9 => string '3.0' (length=3)
//  10 => string '3.1' (length=3)
//  11 => string '3.2' (length=3)
//  12 => string '3.3' (length=3)
//  13 => string '3.4' (length=3)
//  15 => string '3.6' (length=3)
//  16 => string '3.7' (length=3)
//  17 => string '3.8' (length=3)
//  21 => string '4.3' (length=3)
//  22 => string '4.4' (length=3)
//  24 => string '5.3' (length=3)
//  25 => string '5.4' (length=3)



//array (size=19)
//  0 => string '2.5' (length=3)
//  1 => string '3.5' (length=3)
//  2 => string '4.5' (length=3)
//  3 => string '5.5' (length=3)
//  4 => string '6.5' (length=3)
//  5 => string '7.5' (length=3)
//  6 => string '8.5' (length=3)
//  7 => string '3.0' (length=3)
//  8 => string '3.1' (length=3)
//  9 => string '3.2' (length=3)
//  10 => string '3.3' (length=3)
//  11 => string '3.4' (length=3)
//  12 => string '3.6' (length=3)
//  13 => string '3.7' (length=3)
//  14 => string '3.8' (length=3)
//  15 => string '4.3' (length=3)
//  16 => string '4.4' (length=3)
//  17 => string '5.3' (length=3)
//  18 => string '5.4' (length=3)

//object(AppBundle\Entity\Grid)[94]
//  protected 'size' => int 9
//  protected 'tiles' => 
//    array (size=9)
//      0 => 
//        array (size=4)
//          2 => int 2
//          5 => int 9
//          6 => int 1
//          8 => int 6
//      1 => 
//        array (size=4)
//          0 => int 3
//          2 => int 5
//          4 => int 4
//          6 => int 2
//      2 => 
//        array (size=4)
//          1 => int 7
//          2 => int 9
//          3 => int 2
//          4 => int 6
//      3 => 
//        array (size=3)
//          1 => int 5
//          7 => int 1
//          8 => int 9
//      4 => 
//        array (size=7)
//          1 => int 2
//          2 => int 1
//          3 => int 9
//          4 => int 7
//          5 => int 5
//          6 => int 8
//          7 => int 4
//      5 => 
//        array (size=3)
//          0 => int 9
//          1 => int 8
//          7 => int 2
//      6 => 
//        array (size=4)
//          4 => int 9
//          5 => int 1
//          6 => int 7
//          7 => int 6
//      7 => 
//        array (size=4)
//          2 => int 4
//          4 => int 5
//          6 => int 3
//          8 => int 1
//      8 => 
//        array (size=4)
//          0 => int 7
//          2 => int 6
//          3 => int 3
//          6 => int 9
//  protected 'solved' => boolean false
//  protected 'remainingTiles' => int 81