<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event\GridSize;
use AppBundle\Event\StartGameEvent;
use AppBundle\Utils\GridMapper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Finder\Finder ;

class DefaultController extends Controller
{
//    /**
//     * @Route("/", name="homepage")
//     */
//    public function indexAction(Request $request)
//    {
//        // replace this example code with whatever you need
//        return $this->render('default/index.html.twig', [
//            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
//        ]);
//    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
//        var_dump(__DIR__ . "/../../../datas") ;
//        $finder = new Finder() ;
//        $finder->files()->in(__DIR__ . "/../../../datas/9/1") ;
//        foreach($finder as $file)
//        {
//            $path = include($file->getRealpath()) ;
//            var_dump($path) ; die() ;
//        }
        
        $session = $this->get('sudokuSessionService') ;
        $session->getSession()->clear() ;
//        var_dump($session->getSession()->get('grid')) ;
        return $this->render(
                'sudoku/index.html.twig',
                array()
                ) ;
    }
    
    /**
     * @Route("/{size}", name="grid")
     */
    public function gridAction(Request $request, $size=null)
    {
        $gridSize = new GridSize($size) ;
        
        $session = $this->get('sudokuSessionService') ;

        $event = new StartGameEvent($gridSize) ;
        $this->get('event_dispatcher')->dispatch('game.start', $event) ;
//        $aGrid = $this->pickAGrid($gridSize) ;
//        $grid->setTiles($aGrid) ;
        $grid = $session->getGrid() ;
//        var_dump( $session->getGrid()) ;
//        var_dump($grid) ;
//        var_dump(GridMapper::toArray($grid)) ;

        return $this->render(
                'sudoku/grid.html.twig',
                GridMapper::toArray($grid)) ;
    }
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
