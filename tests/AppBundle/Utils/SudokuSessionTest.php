<?php

namespace Tests\AppBundle\Utils;

use AppBundle\Utils\SudokuSession;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Description of SudokuSessionTest
 *
 * @author haclong
 */
class SudokuSessionTest extends \PHPUnit_Framework_TestCase {
    protected $session ;
    protected $grid ;
    protected $values ;
    protected $tiles ;
    
    public function setUp()
    {
        $mockSessionStorage = new MockArraySessionStorage() ;
        $this->session = new Session($mockSessionStorage) ;
        $this->grid = $this->getMockBuilder('AppBundle\Entity\Grid')
                     ->disableOriginalConstructor()
                     ->getMock() ;
        $this->values = $this->getMockBuilder('AppBundle\Entity\Values')
                     ->disableOriginalConstructor()
                     ->getMock() ;
        $this->tiles = $this->getMockBuilder('AppBundle\Entity\Tiles')
                     ->disableOriginalConstructor()
                     ->getMock() ;
    }
    
    public function tearDown()
    {
        $this->session = null ;
        $this->grid = null ;
        $this->values = null ;
        $this->tiles = null ;
    }
    
    public function testConstructor()
    {
        $sudokuSession = new SudokuSession($this->session, $this->grid, $this->values, $this->tiles) ;
        $this->assertInstanceOf('AppBundle\Entity\Grid', $sudokuSession->getGrid()) ;
        $this->assertInstanceOf('AppBundle\Entity\Values', $sudokuSession->getValues()) ;
        $this->assertInstanceOf('AppBundle\Entity\Tiles', $sudokuSession->getTiles()) ;
    }
    
    public function testClear()
    {
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                     ->getMock() ;
        $session->expects($this->once())
                ->method('clear') ;
        $sudokuSession = new SudokuSession($session, $this->grid, $this->values, $this->tiles) ;
        $sudokuSession->clear() ;
    }
}