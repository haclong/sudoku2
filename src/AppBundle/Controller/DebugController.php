<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event\GridSize;
use AppBundle\Entity\Grid;
use AppBundle\Event\GetGridEvent;
use AppBundle\Event\ResetGridEvent;
use AppBundle\Event\StartGameEvent;
use AppBundle\Service\SudokuSessionService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;

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
        $tile = $this->get('tileEntity') ;
        $serial = serialize($tile) ;
//        var_dump(unserialize($serial)) ;
//        $tiles = $this->get('tilesEntity') ;
//        $tiles->setTileset(4) ;
//        $serialized = serialize($tiles) ;
//        var_dump(unserialize($serialized)) ;
        return $this->render('sudoku/debug.html.twig', []);
    }
}
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