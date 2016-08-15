<?php

namespace AppBundle\Controller;

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
        // replace this example code with whatever you need
        return $this->render('sudoku/debug.html.twig', []);
    }
}
