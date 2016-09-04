<?php

namespace Tests\AppBundle\Service;

use AppBundle\Service\SudokuSessionServiceFactory;

/**
 * Description of SudokuSessionServiceFactoryTest
 *
 * @author haclong
 */
class SudokuSessionServiceFactoryTest extends \PHPUnit_Framework_TestCase {
    public function testCreate() {
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                              ->getMock() ;
        $factory = new SudokuSessionServiceFactory() ;
        $this->assertInstanceOf('AppBundle\Service\SudokuSessionService', $factory::create($session)) ;
    }
}