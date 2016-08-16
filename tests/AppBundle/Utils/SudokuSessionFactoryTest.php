<?php

namespace Tests\AppBundle\Utils;

use AppBundle\Utils\SudokuSessionFactory;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

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
    
//    public function testCreateSessionWithAlreadyCreatedBag() {
//        $mockSessionStorage = new MockArraySessionStorage() ;
//        $exception = new \Exception() ;
////        $sudokuBag = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag')
////                          ->setConstructorArgs(array('sudoku'))
////                          ->getMock() ;
//
//        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
//                              ->setConstructorArgs(array($mockSessionStorage))
//                              ->getMock() ;
//        $session->method('registerBag')
//                      ->will($this->throwException(new \Exception)) ;
//        $session->expects($this->once())
//                ->method('getBag') ;
//        
//        $factory = $this->getMockBuilder('AppBundle\Utils\SudokuSessionFactory')
//                              ->getMock() ;
//        $factory->method('create')
//                ->willReturn($session) ;
//   }
}
