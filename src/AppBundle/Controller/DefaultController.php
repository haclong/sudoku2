<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
}
