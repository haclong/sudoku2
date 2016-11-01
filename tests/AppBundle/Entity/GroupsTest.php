<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Groups;
use AppBundle\Entity\Groups\ValuesByGrid;
use AppBundle\Entity\Groups\ValuesByTile;
use ArrayObject;
use RuntimeException;

/**
 * Description of GroupsTest
 *
 * @author haclong
 */
class GroupsTest extends \PHPUnit_Framework_TestCase  {
    protected function setUp()
    {
        $this->arrayObject = new ArrayObject() ;
        $this->valuesByGridObject = new ValuesByGrid() ;
        $this->valuesByTile = new ValuesByTile() ;
    }
    public function testInitialValuesAreNull() {
        $this->setExpectedException(RuntimeException::class) ;
        $groups = new Groups($this->arrayObject, $this->valuesByGridObject, $this->valuesByTile) ;
        $this->assertNull($groups->getSize()) ;
        $this->assertEquals(0, count($groups->getValuesByGrid())) ;
        $this->assertNull($groups->getCol(0)) ;
    }
    
    public function testInitialGetRowThrowsException() {
        $this->setExpectedException(RuntimeException::class) ;
        $groups = new Groups($this->arrayObject, $this->valuesByGridObject, $this->valuesByTile) ;
        $this->assertNull($groups->getRow(0)) ;
    }
    
    public function testInitialGetRegionThrowsException() {
        $this->setExpectedException(RuntimeException::class) ;
        $groups = new Groups($this->arrayObject, $this->valuesByGridObject, $this->valuesByTile) ;
        $this->assertNull($groups->getRegion(0)) ;
    }
    
    public function testInitIsInitializingDatas() {
        $grid = ['0.0', '0.1', '0.2', '0.3', '1.0', '1.1', '1.2', '1.3', '2.0', '2.1', '2.2', '2.3', '3.0', '3.1', '3.2', '3.3'] ;
        $col0 = ['0.0', '1.0', '2.0', '3.0'] ;
        $col1 = ['0.1', '1.1', '2.1', '3.1'] ;
        $col2 = ['0.2', '1.2', '2.2', '3.2'] ;
        $col3 = ['0.3', '1.3', '2.3', '3.3'] ;
        $row0 = ['0.0', '0.1', '0.2', '0.3'] ;
        $row1 = ['1.0', '1.1', '1.2', '1.3'] ;
        $row2 = ['2.0', '2.1', '2.2', '2.3'] ;
        $row3 = ['3.0', '3.1', '3.2', '3.3'] ;
        $region0 = ['0.0', '0.1', '1.0', '1.1'] ;
        $region1 = ['0.2', '0.3', '1.2', '1.3'] ;
        $region2 = ['2.0', '2.1', '3.0', '3.1'] ;
        $region3 = ['2.2', '2.3', '3.2', '3.3'] ;

        $array = [] ;
        for($i=0; $i<4; $i++)
        {
            $array[] = new ArrayObject($grid) ;
        }
        $valuesByGrid = new ValuesByGrid($array) ;
        
        $groups = new Groups($this->arrayObject, $this->valuesByGridObject, $this->valuesByTile) ;
        $groups->init(4) ;

        $this->assertEquals(4, $groups->getSize()) ;
        $this->assertEquals($col0, $groups->getCol(0)->getArrayCopy()[0]->getArrayCopy()) ;
        $this->assertEquals($col1, $groups->getCol(1)->getArrayCopy()[0]->getArrayCopy()) ;
        $this->assertEquals($col2, $groups->getCol(2)->getArrayCopy()[0]->getArrayCopy()) ;
        $this->assertEquals($col3, $groups->getCol(3)->getArrayCopy()[0]->getArrayCopy()) ;
        $this->assertEquals($row0, $groups->getRow(0)->getArrayCopy()[0]->getArrayCopy()) ;
        $this->assertEquals($row1, $groups->getRow(1)->getArrayCopy()[0]->getArrayCopy()) ;
        $this->assertEquals($row2, $groups->getRow(2)->getArrayCopy()[0]->getArrayCopy()) ;
        $this->assertEquals($row3, $groups->getRow(3)->getArrayCopy()[0]->getArrayCopy()) ;
        $this->assertEquals($region0, $groups->getRegion(0)->getArrayCopy()[0]->getArrayCopy()) ;
        $this->assertEquals($region1, $groups->getRegion(1)->getArrayCopy()[0]->getArrayCopy()) ;
        $this->assertEquals($region2, $groups->getRegion(2)->getArrayCopy()[0]->getArrayCopy()) ;
        $this->assertEquals($region3, $groups->getRegion(3)->getArrayCopy()[0]->getArrayCopy()) ;
        $this->assertEquals($valuesByGrid, $groups->getValuesByGrid()) ;
    }
    
    public function testResetDoesResetValues() {
        $this->setExpectedException(RuntimeException::class) ;
        $groups = new Groups($this->arrayObject, $this->valuesByGridObject, $this->valuesByTile) ;
        $groups->init(4) ;
        $groups->reset() ;
        $this->assertNull($groups->getSize()) ;
        $this->assertEquals(0, count($groups->getValuesByGrid())) ;
        $this->assertNull($groups->getCol(0)) ;
    }
    
    public function testResetGetRowThrowsException() {
        $this->setExpectedException(RuntimeException::class) ;
        $groups = new Groups($this->arrayObject, $this->valuesByGridObject, $this->valuesByTile) ;
        $groups->init(4) ;
        $groups->reset() ;
        $this->assertNull($groups->getRow(0)) ;
    }
    
    public function testResetGetRegionThrowsException() {
        $this->setExpectedException(RuntimeException::class) ;
        $groups = new Groups($this->arrayObject, $this->valuesByGridObject, $this->valuesByTile) ;
        $groups->init(4) ;
        $groups->reset() ;
        $this->assertNull($groups->getRegion(0)) ;
    }
    
    public function testReloadRebuildValuesByGrid() {
        $grid = ['0.0', '0.1', '0.2', '0.3', '1.0', '1.1', '1.2', '1.3', '2.0', '2.1', '2.2', '2.3', '3.0', '3.1', '3.2', '3.3'] ;
        $col0 = ['0.0', '1.0', '2.0'] ;
        $col1 = ['0.1', '1.1', '2.1'] ;
        $col2 = ['0.2', '1.2', '2.2'] ;
        $col3 = ['0.3', '1.3'] ;
        $row0 = ['0.0', '0.1', '0.2', '0.3'] ;
        $row1 = ['1.0', '1.1', '1.2', '1.3'] ;
        $row2 = ['2.0', '2.1', '2.2'] ;
        $region0 = ['0.0', '0.1', '1.0', '1.1'] ;
        $region1 = ['0.2', '0.3', '1.2', '1.3'] ;
        $region2 = ['2.0', '2.1'] ;
        $region3 = ['2.2'] ;

        $array = [] ;
        for($i=0; $i<4; $i++)
        {
            $array[] = new ArrayObject($grid) ;
        }
        $reloadedValuesByGrid = new ValuesByGrid($array) ;

        $array = [] ;
        $array[0] = new ArrayObject(['0.0', '0.1', '0.2', '0.3', '1.0', '1.1', '1.2', '1.3', '2.0', '2.1', '2.2', '2.3', '3.0', '3.1', '3.2', '3.3']) ; 
        $array[1] = new ArrayObject(['0.0', '0.1', '0.2', '0.3', '1.0', '1.1', '1.2', '1.3', '2.0', '2.1', '2.2']) ; 
        $array[2] = new ArrayObject(['0.0', '0.1', '0.2', '0.3', '1.0', '1.1', '1.2', '1.3', '2.0', '2.1', '2.2', '2.3']) ; 
        $valuesByGrid = new ValuesByGrid($array) ;
        
        $grid = $this->getMockBuilder('AppBundle\Entity\Grid')
                     ->getMock() ;
        $grid->method('getSize')->willReturn(4) ;
        $groups = new Groups($this->arrayObject, $this->valuesByGridObject, $this->valuesByTile) ;
        $groups->init(4) ;
        
        $groups->getValuesByGrid()->offsetUnset(3) ;
        $groups->getValuesByGrid()->offsetGet(1)->offsetUnset(15) ;
        $groups->getValuesByGrid()->offsetGet(1)->offsetUnset(14) ;
        $groups->getValuesByGrid()->offsetGet(1)->offsetUnset(13) ;
        $groups->getValuesByGrid()->offsetGet(1)->offsetUnset(12) ;
        $groups->getValuesByGrid()->offsetGet(1)->offsetUnset(11) ;
        $groups->getValuesByGrid()->offsetGet(2)->offsetUnset(15) ;
        $groups->getValuesByGrid()->offsetGet(2)->offsetUnset(14) ;
        $groups->getValuesByGrid()->offsetGet(2)->offsetUnset(13) ;
        $groups->getValuesByGrid()->offsetGet(2)->offsetUnset(12) ;

        $this->assertEquals($valuesByGrid, $groups->getValuesByGrid()) ;
        $this->assertEquals($col0, $groups->getCol(0)->getArrayCopy()[1]->getArrayCopy()) ;
        $this->assertEquals($col1, $groups->getCol(1)->getArrayCopy()[1]->getArrayCopy()) ;
        $this->assertEquals($col2, $groups->getCol(2)->getArrayCopy()[1]->getArrayCopy()) ;
        $this->assertEquals($col3, $groups->getCol(3)->getArrayCopy()[1]->getArrayCopy()) ;
        $this->assertEquals($row0, $groups->getRow(0)->getArrayCopy()[1]->getArrayCopy()) ;
        $this->assertEquals($row1, $groups->getRow(1)->getArrayCopy()[1]->getArrayCopy()) ;
        $this->assertEquals($row2, $groups->getRow(2)->getArrayCopy()[1]->getArrayCopy()) ;
        $this->assertEquals(0, count($groups->getRow(3)->offsetGet(1))) ;
        $this->assertEquals($region0, $groups->getRegion(0)->getArrayCopy()[1]->getArrayCopy()) ;
        $this->assertEquals($region1, $groups->getRegion(1)->getArrayCopy()[1]->getArrayCopy()) ;
        $this->assertEquals($region2, $groups->getRegion(2)->getArrayCopy()[1]->getArrayCopy()) ;
        $this->assertEquals($region3, $groups->getRegion(3)->getArrayCopy()[1]->getArrayCopy()) ;
        
        $this->assertEquals(4, count($groups->getRegion(3)->getArrayCopy()[0])) ;
        $this->assertEquals(1, count($groups->getRegion(3)->getArrayCopy()[1])) ;
        $this->assertEquals(2, count($groups->getRegion(3)->getArrayCopy()[2])) ;
        $this->assertFalse($groups->getRegion(3)->offsetExists(3)) ;

        $groups->reload($grid) ;
        $this->assertEquals($reloadedValuesByGrid, $groups->getValuesByGrid()) ;
    }
    
    public function testImpactedTiles()
    {
        $col2 = ['0.2', '1.2', '2.2', '3.2'] ;
        $row1 = ['1.0', '1.1', '1.2', '1.3'] ;
        $region1 = ['0.2', '0.3', '1.2', '1.3'] ;
        $impactedTiles = array_merge($col2, $row1, $region1) ;
        $groups = new Groups($this->arrayObject, $this->valuesByGridObject, $this->valuesByTile) ;
        $groups->init(4) ;
        $this->assertEquals($impactedTiles, $groups->getImpactedTiles(1, 2)) ;
    }

    public function testGetValuesByTile()
    {
        $tile = [0, 1, 2, 3] ;
        $poppedTile = [1, 3] ;
        $groups = new Groups($this->arrayObject, $this->valuesByGridObject, $this->valuesByTile) ;
        $groups->init(4) ;
        $this->assertEquals($tile, $groups->getValuesByTile()['1.2']) ;
        
        $groups->getValuesByGrid()->offsetGet(0)->offsetUnset(6) ;
        $groups->getValuesByGrid()->offsetGet(2)->offsetUnset(6) ;

        $this->assertEquals($poppedTile, $groups->getValuesByTile()['1.2']) ;
    }
}
