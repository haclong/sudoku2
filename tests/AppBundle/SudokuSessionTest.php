<?php

namespace Tests\AppBundle;

use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Description of SudokuSessionTest
 *
 * @author haclong
 */
class SudokuSessionTest extends \PHPUnit_Framework_TestCase 
{
    public function testSession()
    {
        $mockSessionStorage = new MockArraySessionStorage() ;
        $attributeBag = new AttributeBag('sudoku') ;
        $attributeBag->setName('sudoku') ;
        $session = new Session($mockSessionStorage) ;
        $session->registerBag($attributeBag) ;
        $bag = $session->getBag('sudoku') ;
        
        $this->assertFalse($bag->has('test')) ;
        $bag->set('test', 'hello') ;
        $this->assertTrue($bag->has('test')) ;
        $this->assertEquals('hello', $bag->get('test')) ;
    }
}