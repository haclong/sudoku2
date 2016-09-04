<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event\GridSize;
use AppBundle\Entity\Grid;
use AppBundle\Event\ChooseGridEvent;
use AppBundle\Event\StartGameEvent;
use AppBundle\Utils\GridMapper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
//use Symfony\Component\Finder\Finder ;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $session = $this->get('session') ;
        $session->clear() ;

        $grid = new Grid() ;
        $session->set('grid', $grid) ;
        $array = GridMapper::toArray($session->get('grid')) ;

        $this->debugSession("DefaultController::indexAction") ;
        
        return $this->render(
                'sudoku/index.html.twig',
                $array) ;
    }

    /**
     * @Route("/{size}", name="grid")
     */
    public function gridAction(Request $request, $size=null)
    {
        $gridSize = new GridSize($size) ;
        
        $event = new ChooseGridEvent($gridSize) ;
        $this->get('event_dispatcher')->dispatch('grid.choose', $event) ;

        $session = $this->get('session') ;
        $grid = $session->get('grid') ;
        
        $this->debugSession("DefaultController::gridAction") ;
        $array = GridMapper::toArray($grid) ;
        
        return $this->render(
                'sudoku/grid.html.twig',
                $array) ;
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
//class DefaultController extends Controller
//{
////    /**
////     * @Route("/", name="homepage")
////     */
////    public function indexAction(Request $request)
////    {
////        // replace this example code with whatever you need
////        return $this->render('default/index.html.twig', [
////            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
////        ]);
////    }
//
//    /**
//     * @Route("/", name="homepage")
//     */
//    public function indexAction(Request $request)
//    {
////        var_dump(__DIR__ . "/../../../datas") ;
////        $finder = new Finder() ;
////        $finder->files()->in(__DIR__ . "/../../../datas/9/1") ;
////        foreach($finder as $file)
////        {
////            $path = include($file->getRealpath()) ;
////            var_dump($path) ; die() ;
////        }
//        
////        $session = $this->get('sudokuSessionService') ;
////        $session->getSession()->clear() ;
////        $session->getSession()->save() ;
////        var_dump($session->getSession()->getId()) ;
////        var_dump($session->getGrid()) ;
//        
//        $grid = new Grid() ;
//        $session = $this->get('session') ;
//        $session->set('grid', $grid) ;
//        $session->clear() ;
//        var_dump($session->getId()) ;
//        var_dump($session->get('grid')) ;
//        return $this->render(
//                'sudoku/index.html.twig',
//                array()
//                ) ;
//    }
//    
//    /**
//     * @Route("/{size}", name="grid")
//     */
//    public function gridAction(Request $request, $size=null)
//    {
//        $gridSize = new GridSize($size) ;
//        
////        $service = $this->get('sudokuSessionService') ;
////
////        $event = new StartGameEvent($gridSize) ;
////        $this->get('event_dispatcher')->dispatch('game.start', $event) ;
//////        $aGrid = $this->pickAGrid($size) ;
//////        $grid->setTiles($aGrid) ;
////        $grid = $service->getGrid() ;
////        $service->getSession()->save() ;
//////        $grid->setTiles($aGrid) ;
////        var_dump($service->getSession()->getId()) ;
////        var_dump( $service->getGrid()) ;
//////        var_dump($grid) ;
//////        var_dump(GridMapper::toArray($grid)) ;
//        $event = new StartGameEvent($gridSize) ;
//        $this->get('event_dispatcher')->dispatch('game.start', $event) ;
//
//        $session = $this->get('session') ;
//        $grid = $session->get('grid') ;
//        var_dump($session->getId()) ;
//        var_dump($session->get('grid')) ;
//        
//        return $this->render(
//                'sudoku/grid.html.twig',
//                GridMapper::toArray($grid)) ;
//    }
////    protected function pickAGrid($size)
////    {
////        if($size == 'test') {
////            $array = array() ;
////            $array[0][0] = 2 ;
////            $array[2][5] = 8 ;
////            $array[5][3] = 5 ;
////        } else {
////            $size = (int) $size ;
////            if($size == 4) {
////                $file = __DIR__ . "/../../../datas/4/1/1.php" ;
////            } elseif ($size == 9) {
////                $file = __DIR__ . "/../../../datas/9/1/facile_0.php" ;
////            }
////            
////            $array = include($file) ;
////        }
////        
////        return $array ;
////    }
//}
