<?php

namespace Tests\AppBundle\Persistence;

use AppBundle\Persistence\ValuesSession;

/**
 * Description of ValuesSessionTest
 *
 * @author haclong
 */
class ValuesSessionTest extends \PHPUnit_Framework_TestCase {
    protected $session ;
    protected function setUp() 
    {
        $this->session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
                        ->getMock() ;
    }

    
    public function testGetValuesCallsSessionGet()
    {
        $this->session->expects($this->once())
                ->method('get') 
                ->with($this->equalTo('values'));
        $valuesSession = new ValuesSession($this->session) ;
        $valuesSession->getValues() ;
    }
        
    public function testGetValuesReturnsValues()
    {
        $values = $this->getMockBuilder('AppBundle\Entity\Values')
                     ->getMock() ;
        $this->session->expects($this->once())
                ->method('get')
                ->will($this->returnValue($values));
        $valuesSession = new ValuesSession($this->session) ;
        $valuesSession->setValues($values) ;
        $this->assertEquals($values, $valuesSession->getValues()) ;
    }
        
    public function testSetValuesCallsSessionSet()
    {
        $values = $this->getMockBuilder('AppBundle\Entity\Values')
                     ->getMock() ;
        $this->session->expects($this->once())
                ->method('set') 
                ->with($this->equalTo('values'), $values);
        $valuesSession = new ValuesSession($this->session) ;
        $valuesSession->setValues($values) ;
    }
    
    public function testValuesStoredReturnTrue()
    {
        $values = $this->getMockBuilder('AppBundle\Entity\Values')
                     ->getMock() ;
        $this->session->method('get')
                      ->with('values')
                      ->willReturn($values) ;
        $valuesSession = new ValuesSession($this->session) ;
        $this->assertTrue($valuesSession->isReady()) ;
    }
    
    public function testValuesNotStoredReturnFalse()
    {
        $this->session->method('get')
                      ->with('values')
                      ->willReturn(null) ;
        $valuesSession = new ValuesSession($this->session) ;
        $this->assertFalse($valuesSession->isReady()) ;
    }
}
