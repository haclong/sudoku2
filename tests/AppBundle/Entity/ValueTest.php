<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Value;
use AppBundle\Exception\InvalidFigureCountException;

/**
 * Description of ValueTest
 *
 * @author haclong
 */
class ValueTest extends \PHPUnit_Framework_TestCase 
{
    public function testGridSize() {
        $value = new Value() ;
        $value->setGridSize(4) ;
        $this->assertEquals($value->getGridSize(), 4) ;
        $value->add(9) ;
        $value->add(8) ;
        $this->assertEquals(count($value->getValues()), 2) ;
        $value->add(8) ;
        $this->assertEquals(count($value->getValues()), 2) ;
        $value->reset() ;
        $this->assertEquals(count($value->getValues()), 0) ;
    }
    
    public function testValueAdd()
    {
        $value = new Value() ;
        $value->setGridSize(4) ;
        $value->add(9) ;
        $value->add(8) ;
        $this->assertEquals(count($value->getValues()), 2) ;
        $value->add(8) ;
        $this->assertEquals(count($value->getValues()), 2) ;
    }
    
    public function testReset()
    {
        $value = new Value() ;
        $value->setGridSize(4) ;
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
        $value = new Value() ;
        $value->setGridSize(4) ;
        $value->add(9) ;
        $value->add(8) ;
        $value->add(7) ;
        $value->add(6) ;
        $value->add(5) ;
    }
    
    public function testGetValueByKey()
    {
        $value = new Value() ;
        $value->setGridSize(4) ;
        $value->add(9) ;
        $value->add(8) ;
        $value->add(7) ;
        $this->assertEquals($value->getValueByKey(2), 7) ;
    }
    
    public function testGetKeyByValue()
    {
        $value = new Value() ;
        $value->setGridSize(4) ;
        $value->add(9) ;
        $value->add(8) ;
        $value->add(7) ;
        $this->assertEquals($value->getKeyByValue(8), 1) ;
        $this->assertEquals($value->getValueByKey(2), 7) ;
    }
}
