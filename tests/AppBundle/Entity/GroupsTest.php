<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Groups;
use RuntimeException;

/**
 * Description of GroupsTest
 *
 * @author haclong
 */
class GroupsTest extends \PHPUnit_Framework_TestCase  {
    public function testInitialValuesAreNull() {
        $this->setExpectedException(RuntimeException::class) ;
        $groups = new Groups() ;
        $this->assertNull($groups->getSize()) ;
        $this->assertEquals(0, count($groups->getValuesByGrid())) ;
        $this->assertNull($groups->getCol(0)) ;
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

        $valuesByGrid = [] ;
        for($i=0; $i<4; $i++)
        {
            $valuesByGrid[$i] = $grid ;
        }
        
        $groups = new Groups() ;
        $groups->init(4) ;

        $this->assertEquals(4, $groups->getSize()) ;
        $this->assertEquals($col0, $groups->getCol(0)[0]) ;
        $this->assertEquals($col1, $groups->getCol(1)[0]) ;
        $this->assertEquals($col2, $groups->getCol(2)[0]) ;
        $this->assertEquals($col3, $groups->getCol(3)[0]) ;
        $this->assertEquals($row0, $groups->getRow(0)[0]) ;
        $this->assertEquals($row1, $groups->getRow(1)[0]) ;
        $this->assertEquals($row2, $groups->getRow(2)[0]) ;
        $this->assertEquals($row3, $groups->getRow(3)[0]) ;
        $this->assertEquals($region0, $groups->getRegion(0)[0]) ;
        $this->assertEquals($region1, $groups->getRegion(1)[0]) ;
        $this->assertEquals($region2, $groups->getRegion(2)[0]) ;
        $this->assertEquals($region3, $groups->getRegion(3)[0]) ;
        $this->assertEquals($valuesByGrid, $groups->getValuesByGrid()) ;
    }
//    
//    public function testInitIsInitializingDatas() {
//        $col0 = ['0.0', '1.0', '2.0', '3.0'] ;
//        $col1 = ['0.1', '1.1', '2.1', '3.1'] ;
//        $col2 = ['0.2', '1.2', '2.2', '3.2'] ;
//        $col3 = ['0.3', '1.3', '2.3', '3.3'] ;
//        $row0 = ['0.0', '0.1', '0.2', '0.3'] ;
//        $row1 = ['1.0', '1.1', '1.2', '1.3'] ;
//        $row2 = ['2.0', '2.1', '2.2', '2.3'] ;
//        $row3 = ['3.0', '3.1', '3.2', '3.3'] ;
//        $region0 = ['0.0', '0.1', '1.0', '1.1'] ;
//        $region1 = ['0.2', '0.3', '1.2', '1.3'] ;
//        $region2 = ['2.0', '2.1', '3.0', '3.1'] ;
//        $region3 = ['2.2', '2.3', '3.2', '3.3'] ;
//        
//        $valuesByGroup[0] = ['0.0', '0.1', '1.0', '1.1'] ; 
//        $valuesByGroup[1] = ['0.0', '0.1', '1.0', '1.1'] ; 
//        $valuesByGroup[2] = ['0.0', '0.1', '1.0', '1.1'] ; 
//        $valuesByGroup[3] = ['0.0', '0.1', '1.0', '1.1'] ; 
//         
//        $groups = new Groups() ;
//        $groups->init(4) ;
//
//        $this->assertEquals(4, $groups->getSize()) ;
//        $this->assertEquals($col0, $groups->getCol(0)[0]) ;
//        $this->assertEquals($col1, $groups->getCol(1)[0]) ;
//        $this->assertEquals($col2, $groups->getCol(2)[0]) ;
//        $this->assertEquals($col3, $groups->getCol(3)[0]) ;
//        $this->assertEquals($row0, $groups->getRow(0)[0]) ;
//        $this->assertEquals($row1, $groups->getRow(1)[0]) ;
//        $this->assertEquals($row2, $groups->getRow(2)[0]) ;
//        $this->assertEquals($row3, $groups->getRow(3)[0]) ;
//        $this->assertEquals($region0, $groups->getRegion(0)[0]) ;
//        $this->assertEquals($region1, $groups->getRegion(1)[0]) ;
//        $this->assertEquals($region2, $groups->getRegion(2)[0]) ;
//        $this->assertEquals($region3, $groups->getRegion(3)[0]) ;
//        $this->assertEquals($valuesByGroup, $groups->getValuesByGroup()['region'][0]) ;
//    }
    
    public function testResetDoesResetValues() {
        $this->setExpectedException(RuntimeException::class) ;
        $groups = new Groups() ;
        $groups->init(4) ;
        $groups->reset() ;
        $this->assertNull($groups->getSize()) ;
        $this->assertEquals(0, count($groups->getValuesByGrid())) ;
        $this->assertNull($groups->getCol(0)) ;
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

        $reloadedValuesByGrid = [] ;
        for($i=0; $i<4; $i++)
        {
            $reloadedValuesByGrid[$i] = $grid ;
        }

        $valuesByGrid[0] = ['0.0', '0.1', '0.2', '0.3', '1.0', '1.1', '1.2', '1.3', '2.0', '2.1', '2.2', '2.3', '3.0', '3.1', '3.2', '3.3'] ; 
        $valuesByGrid[1] = ['0.0', '0.1', '0.2', '0.3', '1.0', '1.1', '1.2', '1.3', '2.0', '2.1', '2.2'] ; 
        $valuesByGrid[2] = ['0.0', '0.1', '0.2', '0.3', '1.0', '1.1', '1.2', '1.3', '2.0', '2.1', '2.2', '2.3'] ; 

        $grid = $this->getMockBuilder('AppBundle\Entity\Grid')
                     ->getMock() ;
        $grid->method('getSize')->willReturn(4) ;
        $groups = new Groups() ;
        $groups->init(4) ;
        
        unset($groups->getValuesByGrid()[3]) ;
        array_pop($groups->getValuesByGrid()[1]) ;
        array_pop($groups->getValuesByGrid()[1]) ;
        array_pop($groups->getValuesByGrid()[1]) ;
        array_pop($groups->getValuesByGrid()[1]) ;
        array_pop($groups->getValuesByGrid()[1]) ;
        array_pop($groups->getValuesByGrid()[2]) ;
        array_pop($groups->getValuesByGrid()[2]) ;
        array_pop($groups->getValuesByGrid()[2]) ;
        array_pop($groups->getValuesByGrid()[2]) ;

        $this->assertEquals($valuesByGrid, $groups->getValuesByGrid()) ;
        $this->assertEquals($col0, $groups->getCol(0)[1]) ;
        $this->assertEquals($col1, $groups->getCol(1)[1]) ;
        $this->assertEquals($col2, $groups->getCol(2)[1]) ;
        $this->assertEquals($col3, $groups->getCol(3)[1]) ;
        $this->assertEquals($row0, $groups->getRow(0)[1]) ;
        $this->assertEquals($row1, $groups->getRow(1)[1]) ;
        $this->assertEquals($row2, $groups->getRow(2)[1]) ;
        $this->assertTrue(!array_key_exists(1, $groups->getRow(3))) ;
        $this->assertEquals($region0, $groups->getRegion(0)[1]) ;
        $this->assertEquals($region1, $groups->getRegion(1)[1]) ;
        $this->assertEquals($region2, $groups->getRegion(2)[1]) ;
        $this->assertEquals($region3, $groups->getRegion(3)[1]) ;
        
        $this->assertEquals(4, count($groups->getRegion(3)[0])) ;
        $this->assertEquals(1, count($groups->getRegion(3)[1])) ;
        $this->assertEquals(2, count($groups->getRegion(3)[2])) ;
        $this->assertTrue(!array_key_exists(3, $groups->getRegion(3))) ;

        $groups->reload($grid) ;
        $this->assertEquals($reloadedValuesByGrid, $groups->getValuesByGrid()) ;
    }
//
//    public function testReloadRebuildValuesByGroup() {
//        $valuesByGroup[0] = ['0.0', '0.1', '1.0', '1.1'] ; 
//        $valuesByGroup[1] = ['0.0', '0.1'] ; 
//        $valuesByGroup[2] = ['0.0', '0.1', '1.0'] ; 
//
//        $reloadedValuesByGroup[0] = ['0.0', '0.1', '1.0', '1.1'] ; 
//        $reloadedValuesByGroup[1] = ['0.0', '0.1', '1.0', '1.1'] ; 
//        $reloadedValuesByGroup[2] = ['0.0', '0.1', '1.0', '1.1'] ; 
//        $reloadedValuesByGroup[3] = ['0.0', '0.1', '1.0', '1.1'] ; 
//
//        $grid = $this->getMockBuilder('AppBundle\Entity\Grid')
//                     ->getMock() ;
//        $grid->method('getSize')->willReturn(4) ;
//        $groups = new Groups() ;
//        $groups->init(4) ;
//        
//        unset($groups->getRegion(0)[3]) ;
//        array_pop($groups->getRegion(0)[1]) ;
//        array_pop($groups->getRegion(0)[1]) ;
//        array_pop($groups->getRegion(0)[2]) ;
//        $this->assertEquals($valuesByGroup, $groups->getValuesByGroup()['region'][0]) ;
//        
//        $groups->reload($grid) ;
//        $this->assertEquals($reloadedValuesByGroup, $groups->getValuesByGroup()['region'][0]) ;
//    }
    
    public function testImpactedTiles()
    {
        $col2 = ['0.2', '1.2', '2.2', '3.2'] ;
        $row1 = ['1.0', '1.1', '1.2', '1.3'] ;
        $region1 = ['0.2', '0.3', '1.2', '1.3'] ;
        $impactedTiles = array_merge($col2, $row1, $region1) ;
        $groups = new Groups() ;
        $groups->init(4) ;
        $this->assertEquals($impactedTiles, $groups->getImpactedTiles(1, 2)) ;
    }

    public function testGetValuesByTile()
    {
        $tile = [0, 1, 2, 3] ;
        $poppedTile = [2, 3] ;
        $groups = new Groups() ;
        $groups->init(4) ;
        $this->assertEquals($tile, $groups->getValuesByTile()['1.2']) ;
        
        unset($groups->getValuesByGrid()[0][6]) ;
        unset($groups->getValuesByGrid()[1][6]) ;

        $this->assertEquals($poppedTile, $groups->getValuesByTile()['1.2']) ;
    }

}
