<?php

namespace Tests\AppBundle\Utils;

use AppBundle\Utils\SudokuSessionFactory;

/**
 * Description of SudokuSessionFactoryTest
 *
 * @author haclong
 */
class SudokuSessionFactoryTest extends \PHPUnit_Framework_TestCase {
    public function testCreate() {
        $factory = new SudokuSessionFactory() ;
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Session\Session', $factory::create()) ;
    }
}
