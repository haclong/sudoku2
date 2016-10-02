<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Values;
use AppBundle\Exception\InvalidFigureCountException;

/**
 * Description of ValuesTest
 *
 * @author haclong
 */
class ValuesTest extends \PHPUnit_Framework_TestCase 
{
    public function testGridSize() {
        $value = new Values() ;
        $value->init(4) ;
        $this->assertEquals($value->getSize(), 4) ;
        $value->add(9) ;
        $value->add(8) ;
        $this->assertEquals(count($value->getValues()), 2) ;
        $value->add(8) ;
        $this->assertEquals(count($value->getValues()), 2) ;
        $value->reset() ;
        $this->assertEquals(count($value->getValues()), 0) ;
    }
    
    public function testValuesAdd()
    {
        $value = new Values() ;
        $value->init(4) ;
        $value->add(9) ;
        $value->add(8) ;
        $this->assertEquals(count($value->getValues()), 2) ;
        $value->add(8) ;
        $this->assertEquals(count($value->getValues()), 2) ;
    }
    
    public function testReset()
    {
        $value = new Values() ;
        $value->init(4) ;
        $value->add(9) ;
        $value->add(8) ;
        $value->add(7) ;
        $this->assertEquals(count($value->getValues()), 3) ;
        $value->reset() ;
        $this->assertEquals(count($value->getValues()), 0) ;
    }
    
    public function testInvalidFigureCountExceptionThrown()
    {
        $this->setExpectedException(InvalidFigureCountException::class) ;
        $value = new Values() ;
        $value->init(4) ;
        $value->add(9) ;
        $value->add(8) ;
        $value->add(7) ;
        $value->add(6) ;
        $value->add(5) ;
    }
    
    public function testGetValueByKey()
    {
        $value = new Values() ;
        $value->init(4) ;
        $value->add(9) ;
        $value->add(8) ;
        $value->add(7) ;
        $this->assertEquals($value->getValueByKey(2), 7) ;
    }
    
    public function testGetValueByKeyReturnNull()
    {
        $value = new Values() ;
        $this->assertNull($value->getValueByKey(2)) ;
    }
    
    public function testGetKeyByValue()
    {
        $value = new Values() ;
        $value->init(4) ;
        $value->add(9) ;
        $value->add(8) ;
        $value->add(7) ;
        $this->assertEquals($value->getKeyByValue(8), 1) ;
        $this->assertEquals($value->getValueByKey(2), 7) ;
    }
}
