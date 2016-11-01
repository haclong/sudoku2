<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event\GridSize;
use AppBundle\Entity\Event\TilesLoaded;
use AppBundle\Entity\Groups;
use AppBundle\Event\InitGameEvent;
use AppBundle\Event\LoadGameEvent;
use AppBundle\Event\ResetGameEvent;
use AppBundle\Event\SetGameEvent;
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
//        $file = __DIR__ . "/../../../datas/4/1/1.php" ;
//        $array = include($file) ;
$g4easy[0][1] = 1 ;
$g4easy[2][0] = 2 ;
$g4easy[1][3] = 3 ;
//$g4easy[3][1] = 3 ;
//$g4easy[3][2] = 4 ;
//$g4easy[3][3] = 1 ;

        $service = $this->get('groupsService') ;
        // on initialise les objets en session
        $sudokuEntities = $this->get('sudokuEntities') ;
        $event = new SetGameEvent($sudokuEntities) ;
        $this->get('event_dispatcher')->dispatch(SetGameEvent::NAME, $event) ;
        
        $gridSize = new GridSize(4) ;
        $event = new InitGameEvent($gridSize) ;
        $this->get('event_dispatcher')->dispatch(InitGameEvent::NAME, $event) ;
        
        $loadedGrid = new TilesLoaded(4, $g4easy) ;
        $event = new LoadGameEvent($loadedGrid) ;
        $this->get('event_dispatcher')->dispatch(LoadGameEvent::NAME, $event) ;
        
        $grid = $this->get('gridSession')->getGrid() ;
        $tiles = $this->get('tilesSession')->getTiles() ;
        $groups = $this->get('groupsSession')->getGroups() ;

//        echo "0.1::0" ;
//        $service->set($groups, 0, 0, 1) ;
//        echo "1.0::1" ;
//        $service->set($groups, 1, 1, 0) ;
//        echo "2.2::0" ;
//        $service->set($groups, 0, 2, 2) ;
//        echo "3.1::1" ;
//        $service->set($groups, 1, 3, 1) ;
//        echo "3.2::2" ;
//        $service->set($groups, 2, 3, 2) ;
        
//        var_dump($groups->getCol(0)->offsetGet(0)->getIterator()->current()) ;
        var_dump($groups) ;
        for($i = 0; $i<4; $i++)
        {
            var_dump($groups->getRow($i)) ;
        }
//        var_dump($groups->getCol(0)) ;
//        var_dump($groups->getRegion(1)) ;
//        var_dump($groups->getRegion(2)) ;
//        var_dump($groups->getRegion(3)) ;
//        var_dump($groups->valuesByGroup) ;
//        var_dump($groups->getValuesByGrid()) ;
//        var_dump($groups->tilesByGroup) ;
        var_dump($groups->getValuesByTile()) ;
//        $grid = $this->get('gridSession')->getGrid() ;
//        $tiles = $this->get('tilesSession')->getTiles() ;
//        $groups = $this->get('groupsSession')->getGroups() ;
//        
//        // on initialise les objets en session
//        $sudokuEntities = $this->get('sudokuEntities') ;
//        $event = new SetGameEvent($sudokuEntities) ;
//        $this->get('event_dispatcher')->dispatch(SetGameEvent::NAME, $event) ;
//        
//        $gridSize = new GridSize(4) ;
//        $event = new InitGameEvent($gridSize) ;
//        $this->get('event_dispatcher')->dispatch(InitGameEvent::NAME, $event) ;
//        
//        $loadedGrid = new TilesLoaded(4, $array) ;
//        $event = new LoadGameEvent($loadedGrid) ;
//        $this->get('event_dispatcher')->dispatch(LoadGameEvent::NAME, $event) ;
//
////        $crawler = $this->client->request(
////                            'POST',
////                            '/api/tile/set',
////                            array(),
////                            array(),
////                            array('CONTENT_TYPE' => 'application/json'),
////                            '{"tile":{"id":"t.0.0","value":"2"}}');
//        
//        // tests sur le retour en json
//        $grid = $this->get('gridSession')->getGrid() ;
//        $tiles = $this->get('tilesSession')->getTiles() ;
//        $groups = $this->get('groupsSession')->getGroups() ;
////        var_dump($groups) ;
////        $grid->reload() ;
////        $groups->reload($grid) ;
//        
//        var_dump($tiles->getTilesToSolve()) ;
//        var_dump($tiles->getTileset()) ;
//////

//        //        var_dump($groups) ;
//        $array = $tiles->getTilesToSolve() ;
//        foreach($grid->getTiles() as $row => $cols)
//        {
//            foreach($cols as $col => $value)
//            {
//        $array = array_flip($array) ;
//        unset($array[$row . '.' . $col]) ;
//        $array = array_flip($array) ;
//            }
//        }
//        var_dump($array) ;
        
//        foreach($array as $key => $tile)
//        {
//            if($tile == '4.6')
//            {
//                unset($array[$key]) ;
//            }
//        }
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