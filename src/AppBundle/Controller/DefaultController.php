<?php

namespace AppBundle\Controller;

use AppBundle\Exception\InvalidGridSizeException;
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
        $gridSize = (int) $size ;
        try {
            $this->validateGridSize($gridSize) ;
        } catch (InvalidGridSizeException $ex) {
            return $this->render(
                    'sudoku/error.html.twig',
                    array('msg' => $ex->getMessage() )
                    ) ;
        }
        
        return $this->render(
                'sudoku/grid.html.twig',
                array('size' => $gridSize, 
                      'msg' => '',
                      'post' => array(),
                      'sqrt' => sqrt($gridSize) )
                ) ;
    }

    protected function validateGridSize($size)
    {
        $root = sqrt($size) ;
        if(fmod($root, 1) != 0) 
        {
            throw new InvalidGridSizeException('Invalid grid size : ' . $size) ;
        }        
    }
}
