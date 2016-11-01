<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Groups\ValuesByGrid;
use AppBundle\Event\DeduceTileEvent;
use AppBundle\Event\ValidateTileSetEvent;
use AppBundle\Exception\AlreadySetTileException;
use AppBundle\Service\GroupsService;
use ArrayObject;
use ReflectionClass;
/**
 * Description of GroupsServiceTest
 *
 * @author haclong
 */
class GroupsServiceTest extends \PHPUnit_Framework_TestCase {
    protected $dispatcher ;
    protected $deduceTileEvent ;
    protected $validateTileSetEvent ;
    protected $groups ;
    
    protected function setUp()
    {
        $this->dispatcher = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcher')
                                 ->getMock() ;

        $tileLastPossibility = $this->getMockBuilder('AppBundle\Entity\Event\TileLastPossibility')
                                    ->getMock() ;
        $this->deduceTileEvent = $this->getMockBuilder('AppBundle\Event\DeduceTileEvent')
                                      ->setConstructorArgs(array($tileLastPossibility))
                                      ->getMock() ;
        $this->deduceTileEvent->method('getTile')
                              ->willReturn($tileLastPossibility) ;

        $tileset = $this->getMockBuilder('AppBundle\Entity\Event\TileSet')
                        ->getMock() ;
        $this->validateTileSetEvent = $this->getMockBuilder('AppBundle\Event\ValidateTileSetEvent')
                                   ->setConstructorArgs(array($tileset))
                                   ->getMock() ;
        $this->validateTileSetEvent->method('getTile')
                                   ->willReturn($tileset) ;
        
        $this->groups = $this->getMockBuilder('AppBundle\Entity\Groups')
                             ->disableOriginalConstructor()
                             ->getMock() ;
//        $this->groups->method('getSize')
//                    ->willReturn(9) ;
    }
    
    protected function tearDown() {
        $this->dispatcher = null ;
        $this->validateTileSetEvent = null ;
        $this->deduceTileEvent = null ;
        $this->groups = null ;
    }
    
    public function testSetThrowsAlreadySetTileExceptionInRow()
    {
        $row = new ArrayObject([0 => new ArrayObject([0,1]), 1 => NULL, 2 => new ArrayObject([0,1])]) ;
        $col = new ArrayObject([0 => new ArrayObject([0,1]), 1 => new ArrayObject([0]), 2 => new ArrayObject([0,1])]) ;
        $reg = new ArrayObject([0 => new ArrayObject([0,1]), 1 => new ArrayObject([1]), 2 => new ArrayObject([0,1])]) ;
        $this->setExpectedException(AlreadySetTileException::class) ;
        $this->dispatcher->expects($this->never())
                         ->method('dispatch')
                         ->with(ValidateTileSetEvent::NAME, $this->equalTo($this->validateTileSetEvent)) ;
        
        $this->groups->method('getRow')
                     ->willReturn($row) ;
        $this->groups->method('getCol')
                     ->willReturn($col) ;
        $this->groups->method('getRegion')
                     ->willReturn($reg) ;
        $service = new GroupsService($this->dispatcher, $this->validateTileSetEvent, $this->deduceTileEvent) ;
        $service->set($this->groups, 1, 0, 0) ;
    } 
    
    public function testSetThrowsAlreadySetTileExceptionInCol()
    {
        $row = new ArrayObject([0 => new ArrayObject([0,1]), 1 => new ArrayObject([0]), 2 => new ArrayObject([0,1])]) ;
        $col = new ArrayObject([0 => new ArrayObject([0,1]), 1 => NULL, 2 => new ArrayObject([0,1])]) ;
        $reg = new ArrayObject([0 => new ArrayObject([0,1]), 1 => new ArrayObject([1]), 2 => new ArrayObject([0,1])]) ;
        $this->setExpectedException(AlreadySetTileException::class) ;
        $this->dispatcher->expects($this->never())
                         ->method('dispatch')
                         ->with(ValidateTileSetEvent::NAME, $this->equalTo($this->validateTileSetEvent)) ;
        
        $this->groups->method('getRow')
                     ->willReturn($row) ;
        $this->groups->method('getCol')
                     ->willReturn($col) ;
        $this->groups->method('getRegion')
                     ->willReturn($reg) ;
        $service = new GroupsService($this->dispatcher, $this->validateTileSetEvent, $this->deduceTileEvent) ;
        $service->set($this->groups, 1, 0, 0) ;
    }
    
    public function testSetThrowsAlreadySetTileExceptionInRegion()
    {
        $row = new ArrayObject([0 => new ArrayObject([0,1]), 1 => new ArrayObject([0]), 2 => new ArrayObject([0,1])]) ;
        $col = new ArrayObject([0 => new ArrayObject([0,1]), 1 => new ArrayObject([1]), 2 => new ArrayObject([0,1])]) ;
        $reg = new ArrayObject([0 => new ArrayObject([0,1]), 1 => NULL, 2 => new ArrayObject([0,1])]) ;
        $this->setExpectedException(AlreadySetTileException::class) ;
        $this->dispatcher->expects($this->never())
                         ->method('dispatch')
                         ->with(ValidateTileSetEvent::NAME, $this->equalTo($this->validateTileSetEvent)) ;
        
        $this->groups->method('getRow')
                     ->willReturn($row) ;
        $this->groups->method('getCol')
                     ->willReturn($col) ;
        $this->groups->method('getRegion')
                     ->willReturn($reg) ;
        $service = new GroupsService($this->dispatcher, $this->validateTileSetEvent, $this->deduceTileEvent) ;
        $service->set($this->groups, 1, 0, 0) ;
    }

    public function testSetDispatchSetTileValidateEvent()
    {
        $row = new ArrayObject([0 => new ArrayObject([0,1]), 1 => new ArrayObject([0]), 2 => new ArrayObject([0,1])]) ;
        $col = new ArrayObject([0 => new ArrayObject([0,1]), 1 => new ArrayObject([0]), 2 => new ArrayObject([0,1])]) ;
        $reg = new ArrayObject([0 => new ArrayObject([0,1]), 1 => new ArrayObject([0]), 2 => new ArrayObject([0,1])]) ;
        $impactedTiles = ['0.1', '0.3'] ;
        $valuesByGroup['col'][0][0] = ['0.0', '0.1', '0.2', '0.3'] ;
        $valuesByGroup['col'][0][1] = ['0.0', '0.1', '0.2', '0.3'] ;
        $valuesByGroup['col'][0][2] = ['0.0', '0.1', '0.2', '0.3'] ;
        $valuesByGroup['col'][0][3] = ['0.0', '0.1', '0.2', '0.3'] ;
        $tiles = new ArrayObject(['0.0', '0.1', '0.2', '0.3']) ;
        $valuesByGrid = new ArrayObject([$tiles, $tiles, $tiles, $tiles]) ;

        $this->dispatcher->expects($this->once())
                         ->method('dispatch')
                         ->with(ValidateTileSetEvent::NAME, $this->equalTo($this->validateTileSetEvent)) ;
        
        $this->groups->method('getRow')
                     ->willReturn($row) ;
        $this->groups->method('getCol')
                     ->willReturn($col) ;
        $this->groups->method('getRegion')
                     ->willReturn($reg) ;
        $this->groups->method('getImpactedTiles')
                     ->willReturn($impactedTiles) ;
        $this->groups->method('getValuesByGroup')
                    ->willReturn($valuesByGroup) ;
        $this->groups->method('getValuesByGrid')
                    ->willReturn($valuesByGrid) ;
        $this->groups->method('getValuesByTile')
                    ->willReturn(array()) ;
        $service = new GroupsService($this->dispatcher, $this->validateTileSetEvent, $this->deduceTileEvent) ;
        $service->set($this->groups, 1, 0, 0) ;
    }
    
    public function testSetDispatchLastValueInGroup()
    {
        $row = new ArrayObject([0 => new ArrayObject(['0.0']), 
                  1 => new ArrayObject(['2.3']), 
                  2 => new ArrayObject(['1.2', '3.0'])]) ;
        $col = new ArrayObject([0 => new ArrayObject(['0.0']), 
                  1 => new ArrayObject(['2.3']), 
                  2 => new ArrayObject(['1.2', '3.0'])]) ;
        $region = new ArrayObject([0 => new ArrayObject(['0.0']), 
                  1 => new ArrayObject(['2.3']), 
                  2 => new ArrayObject(['1.2', '3.0'])]) ;
        $valuesByGrid = new ArrayObject([
                    new ArrayObject(['1.0', '1.1', '1.3']),
                    new ArrayObject(['1.1', '1.2', '1.3']),
                    new ArrayObject(['1.0', '1.1', '1.2', '1.3']),
                    new ArrayObject(['1.0', '1.1'])
                    ]) ;
        $impactedTiles = ['0.0', '1.2', '1.3'] ;
        $valuesByTile = ['1.0' => [0, 1, 3]] ;

        $this->groups->method('getSize')->willReturn(1) ;
        $this->groups->method('getRow')->willReturn($row) ;
        $this->groups->method('getCol')->willReturn($col) ;
        $this->groups->method('getRegion')->willReturn($region) ;
        $this->groups->method('getImpactedTiles')->willReturn($impactedTiles) ;
        $this->groups->method('getValuesByGrid')->willReturn($valuesByGrid) ;
        $this->groups->method('getValuesByTile')->willReturn($valuesByTile) ;

        $this->dispatcher->expects($this->at(1))
                         ->method('dispatch')
                         ->with(DeduceTileEvent::NAME, $this->equalTo($this->deduceTileEvent)) ;
        $this->dispatcher->expects($this->at(2))
                         ->method('dispatch')
                         ->with(DeduceTileEvent::NAME, $this->equalTo($this->deduceTileEvent)) ;


//        $row = new ArrayObject([0 => new ArrayObject([0,1]), 1 => new ArrayObject([0]), 2 => new ArrayObject([0,1])]) ;
//        $col = new ArrayObject([0 => new ArrayObject([0,1]), 1 => new ArrayObject([0]), 2 => new ArrayObject([0,1])]) ;
//        $reg = new ArrayObject([0 => new ArrayObject([0,1]), 1 => new ArrayObject([0]), 2 => new ArrayObject([0,1])]) ;
//        $valuesByGroup['col'][0][0] = ['0.0', '0.1', '0.2', '0.3'] ;
//        $valuesByGroup['col'][0][1] = ['0.0', '0.1', '0.2', '0.3'] ;
//        $valuesByGroup['col'][0][2] = ['0.3'] ;
//        $valuesByGroup['col'][0][3] = ['0.0', '0.1', '0.2', '0.3'] ;
//        $tiles0 = new ArrayObject(['0.0', '0.1', '0.2', '0.3']) ;
//        $tiles1 = new ArrayObject(['0.0', '0.1', '0.2', '0.3']) ;
//        $tiles2 = new ArrayObject(['0.3']) ;
//        $tiles3 = new ArrayObject(['0.0', '0.1', '0.2', '0.3']) ;
//        $valuesByGrid = new ArrayObject([$tiles0, $tiles1, $tiles2, $tiles3]) ;
//        
//        $this->dispatcher->expects($this->at(1))
//                         ->method('dispatch')
//                         ->with(DeduceTileEvent::NAME, $this->equalTo($this->deduceTileEvent)) ;
//        
//        $this->groups->method('getRow')
//                     ->willReturn($row) ;
//        $this->groups->method('getCol')
//                     ->willReturn($col) ;
//        $this->groups->method('getRegion')
//                     ->willReturn($reg) ;
//        $this->groups->method('getImpactedTiles')
//                     ->willReturn(array()) ;
//        $this->groups->method('getValuesByGrid')
//                    ->willReturn($valuesByGrid) ;
//        $this->groups->method('getValuesByTile')
//                    ->willReturn(array()) ;
        $service = new GroupsService($this->dispatcher, $this->validateTileSetEvent, $this->deduceTileEvent) ;
        $service->set($this->groups, 1, 0, 0) ;
    }
    
    public function testSetDispatchLastValueInTile()
    {
        $row = new ArrayObject([0 => new ArrayObject(['0.0']), 
                  1 => new ArrayObject(['2.3']), 
                  2 => new ArrayObject(['1.2', '3.0'])]) ;
        $col = new ArrayObject([0 => new ArrayObject(['0.0']), 
                  1 => new ArrayObject(['2.3']), 
                  2 => new ArrayObject(['1.2', '3.0'])]) ;
        $region = new ArrayObject([0 => new ArrayObject(['0.0']), 
                  1 => new ArrayObject(['2.3']), 
                  2 => new ArrayObject(['1.2', '3.0'])]) ;
        $valuesByGrid = new ArrayObject([
                    new ArrayObject(['1.0', '1.1', '1.3']),
                    new ArrayObject(['1.1', '1.2', '1.3']),
                    new ArrayObject(['1.0', '1.1', '1.2', '1.3']),
                    new ArrayObject(['1.0', '1.1'])
                    ]) ;
        $impactedTiles = ['0.0', '1.2', '1.3'] ;
//        $row = new ArrayObject([0 => new ArrayObject([0,1]), 1 => new ArrayObject([0]), 2 => new ArrayObject([0,1])]) ;
//        $col = new ArrayObject([0 => new ArrayObject([0,1]), 1 => new ArrayObject([0]), 2 => new ArrayObject([0,1])]) ;
//        $reg = new ArrayObject([0 => new ArrayObject([0,1]), 1 => new ArrayObject([0]), 2 => new ArrayObject([0,1])]) ;
        $valuesByTile = ['1.0' => [3]] ;
//        $valuesByTile = ['1.2' => [0, 1, 3],
//                         '2.2' => [0, 2, 3],
//                         '2.3' => [0, 3],
//                         '3.0' => [0, 2, 3],
//                         '3.2' => [0, 1, 2, 3],
//                         '3.3' => [0, 1, 3],
//                         '0.2' => [1, 3],
//                         '0.3' => [1, 3],
//                         '1.1' => [1, 3],
//                         '0.0' => [2, 3],
//                         '2.1' => [2, 3],
//                         '3.1' => [2, 3],
//                         '1.0' => [3],
//                        ] ;
//
//        $index0 = new ArrayObject(['1.2', '2.2', '2.3', '3.0', '3.2', '3.3']) ;
//        $index1 = new ArrayObject(['0.2', '0.3', '1.1', '1.2', '3.2', '3.3']) ;
//        $index2 = new ArrayObject(['0.0', '2.1', '2.2', '3.0', '3.1', '3.2']) ;
//        $index3 = new ArrayObject(['0.0', '0.2', '0.3', '1.0', '1.1', '1.2', '2.1', '2.2', '2.3', '3.0', '3.1', '3.2', '3.3']) ;
//        $valuesByGrid = new ValuesByGrid([$index0, $index1, $index2, $index3]) ;
//       
//        $this->dispatcher->expects($this->at(1))
//                         ->method('dispatch')
//                         ->with(ValidateTileSetEvent::NAME, $this->equalTo($this->validateTileSetEvent)) ;
        $this->dispatcher->expects($this->at(1))
                         ->method('dispatch')
                         ->with(DeduceTileEvent::NAME, $this->equalTo($this->deduceTileEvent)) ;
//        
//        $this->groups->method('getRow')->with($this->equalTo(0))
//                     ->willReturn(new ValuesByGrid([new ArrayObject([]), new ArrayObject(['0.2', '0.3']), new ArrayObject(['0.0']), new ArrayObject(['0.0', '0.2', '0.3'])])) ;
//        $this->groups->method('getRow')->with($this->equalTo(1))
//                     ->willReturn(new ValuesByGrid([new ArrayObject(['1.2']), new ArrayObject(['1.1', '1.2']), new ArrayObject([]), new ArrayObject(['1.0', '1.1', '1.2'])])) ;
//        $this->groups->method('getRow')->with($this->equalTo(2))
//                     ->willReturn(new ValuesByGrid([new ArrayObject(['2.2', '2.3']), new ArrayObject([]), new ArrayObject(['2.1', '2.2']), new ArrayObject(['2.1', '2.2', '2.3'])])) ;
//        $this->groups->method('getRow')->with($this->equalTo(3))
//                     ->willReturn(new ValuesByGrid([new ArrayObject(['3.0', '3.2', '3.3']), new ArrayObject(['3.2', '3.3']), new ArrayObject(['3.0', '3.1', '3.2']), new ArrayObject(['3.0', '3.1', '3.2', '3.3'])])) ;

//        $this->groups->method('getCol')
//                     ->willReturn($col) ;
//        $this->groups->method('getRegion')
//                     ->willReturn($reg) ;
        $this->groups->method('getSize')->willReturn(1) ;
        $this->groups->method('getRow')->with(0)->willReturn($row) ;
        $this->groups->method('getCol')->with(0)->willReturn($col) ;
        $this->groups->method('getRegion')->with(0)->willReturn($region) ;
        $this->groups->method('getImpactedTiles')->willReturn($impactedTiles) ;
        $this->groups->method('getValuesByGrid')->willReturn($valuesByGrid) ;
        $this->groups->method('getValuesByTile')->willReturn($valuesByTile) ;
        $service = new GroupsService($this->dispatcher, $this->validateTileSetEvent, $this->deduceTileEvent) ;
        $service->set($this->groups, 1, 0, 0) ;
    }
    
    public function testProtectedCheckAlreadySetTile()
    {
        $checkAlreadySetTileMethod = self::getMethod('checkAlreadySetTile') ;
                
        $row = new ArrayObject([0 => new ArrayObject(['0.0']), 
                  1 => new ArrayObject(['2.3']), 
                  2 => new ArrayObject(['1.2', '3.0'])]) ;
        $col = new ArrayObject([0 => new ArrayObject(['0.0']), 
                  1 => new ArrayObject(['2.3']), 
                  2 => new ArrayObject(['1.2', '3.0'])]) ;
        $region = new ArrayObject([0 => new ArrayObject(['0.0']), 
                  1 => new ArrayObject(['2.3']), 
                  2 => new ArrayObject(['1.2', '3.0'])]) ;

        $this->groups->method('getSize')->willReturn(4) ;
        $this->groups->method('getRow')->with(2)->willReturn($row) ;
        $this->groups->method('getCol')->with(3)->willReturn($col) ;
        $this->groups->method('getRegion')->with(3)->willReturn($region) ;
        
        try {
            $service = new GroupsService($this->dispatcher, $this->validateTileSetEvent, $this->deduceTileEvent) ;
            $checkAlreadySetTileMethod->invokeArgs($service, [$this->groups, '1', 2, 3]) ;
            $this->assertTrue(true) ;
        } catch (AlreadySetTileException $expected)
        {
            $this->fail('An expected exception has been raised.');
        }
    }
    
    public function testProtectedCheckValueValid()
    {
        $checkValueMethod = self::getMethod('checkValue') ;
                
        $groups = new ArrayObject([1 => ['0.0']]) ;
        
        try {
            $service = new GroupsService($this->dispatcher, $this->validateTileSetEvent, $this->deduceTileEvent) ;
            $checkValueMethod->invokeArgs($service, [$groups, '1', 'col.2']) ;
            $this->assertTrue(true) ;
        } catch (AlreadySetTileException $expected)
        {
            $this->fail('An expected exception has been raised.');
        }
    }
    
    public function testProtectedCheckValueReturnsException()
    {
        $checkValueMethod = self::getMethod('checkValue') ;
                
        $groups = new ArrayObject([1 => []]) ;
        
        $this->setExpectedException(AlreadySetTileException::class, '1 is already set in col.2') ;

        $service = new GroupsService($this->dispatcher, $this->validateTileSetEvent, $this->deduceTileEvent) ;
        $checkValueMethod->invokeArgs($service, [$groups, '1', 'col.2']) ;
    }
    
    public function testProtectedDiscardValuesInTile()
    {
        $discardValuesInTileMethod = self::getMethod('discardValuesInTile') ;
                
        $valuesByGrid = new ArrayObject([
                    new ArrayObject(['1.0', '1.1', '1.3']),
                    new ArrayObject(['1.1', '1.2', '1.3']),
                    new ArrayObject(['1.0', '1.1', '1.2', '1.3']),
                    new ArrayObject(['1.0', '1.1'])
                    ]) ;
        
        $expectedValuesByGrid = new ArrayObject([
                    new ArrayObject([1 => '1.1', 2 => '1.3']),
                    new ArrayObject(['1.1', '1.2', '1.3']),
                    new ArrayObject([1 => '1.1', 2 => '1.2', 3 => '1.3']),
                    new ArrayObject([1 => '1.1'])
                    ]) ;
        
        $this->groups->method('getValuesByGrid')->willReturn($valuesByGrid) ;

        $service = new GroupsService($this->dispatcher, $this->validateTileSetEvent, $this->deduceTileEvent) ;
        $discardValuesInTileMethod->invokeArgs($service, [$this->groups, '1.0']) ;
        $this->assertEquals($expectedValuesByGrid->getArrayCopy(), $valuesByGrid->getArrayCopy()) ;
    }
    
    public function testProtectedDiscard()
    {
        $discardMethod = self::getMethod('discard') ;

        $tilesForIndex = new ArrayObject(['0.0', '0.1', '0.2', '0.3', '1.0', '1.1', '1.2', '1.3']) ;
        $impactedTiles = ['0.0', '1.2', '1.3'] ;
        
        $this->assertEquals(8, count($tilesForIndex)) ;
        $service = new GroupsService($this->dispatcher, $this->validateTileSetEvent, $this->deduceTileEvent) ;
        $discardMethod->invokeArgs($service, [$tilesForIndex, $impactedTiles]) ;
        $this->assertEquals(5, count($tilesForIndex)) ;
        $this->assertEquals([1 => '0.1', 2 => '0.2', 3 => '0.3', 4=> '1.0', 5=> '1.1'], $tilesForIndex->getArrayCopy()) ;
    }
    
    public function testProtectedCheckLastValueInGroups()
    {
        $checkLastValueInGroupsMethod = self::getMethod('checkLastValueInGroups') ;
        
        $row = [0 => new ArrayObject(['0.0']), 
                  1 => new ArrayObject(['2.3']), 
                  2 => new ArrayObject(['1.2', '3.0'])] ;
        $col = [0 => new ArrayObject(['0.0']), 
                  1 => new ArrayObject(['2.3']), 
                  2 => new ArrayObject(['1.2', '3.0'])] ;
        $region = [0 => new ArrayObject(['0.0']), 
                  1 => new ArrayObject(['2.3']), 
                  2 => new ArrayObject(['1.2', '3.0'])] ;

        $this->dispatcher->expects($this->exactly(6))
                         ->method('dispatch')
                         ->with(DeduceTileEvent::NAME, $this->equalTo($this->deduceTileEvent)) ;

        $this->groups->method('getSize')->willReturn(1) ;
        $this->groups->method('getRow')->willReturn($row) ;
        $this->groups->method('getCol')->willReturn($col) ;
        $this->groups->method('getRegion')->willReturn($region) ;
        
        $service = new GroupsService($this->dispatcher, $this->validateTileSetEvent, $this->deduceTileEvent) ;
        $checkLastValueInGroupsMethod->invokeArgs($service, [$this->groups]) ;
    }
    
    public function testProtectedCheckLastValueInGroup()
    {
        $checkLastValueInGroupMethod = self::getMethod('checkLastValueInGroup') ;
        
        $group = [0 => new ArrayObject(['0.0']), 
                  1 => new ArrayObject(['2.3']), 
                  2 => new ArrayObject(['1.2', '3.0'])] ;

        $this->dispatcher->expects($this->exactly(2))
                         ->method('dispatch')
                         ->with(DeduceTileEvent::NAME, $this->equalTo($this->deduceTileEvent)) ;

        $service = new GroupsService($this->dispatcher, $this->validateTileSetEvent, $this->deduceTileEvent) ;
        $checkLastValueInGroupMethod->invokeArgs($service, [$group]) ;
    }

    public function testProtectedCheckLastValueInTile()
    {
        $checkLastValueInTileMethod = self::getMethod('checkLastValueInTile') ;

        $valuesByTile = ['1.0' => [3]] ;
        
        $this->dispatcher->expects($this->once())
                         ->method('dispatch')
                         ->with(DeduceTileEvent::NAME, $this->equalTo($this->deduceTileEvent)) ;

        $this->groups->method('getValuesByTile')
                    ->willReturn($valuesByTile) ;
        $service = new GroupsService($this->dispatcher, $this->validateTileSetEvent, $this->deduceTileEvent) ;
        $checkLastValueInTileMethod->invokeArgs($service, [$this->groups]) ;
    }

    protected static function getMethod($name) {
        $class = new ReflectionClass('AppBundle\Service\GroupsService');
        $method = $class->getMethod($name) ;
        $method->setAccessible(true) ;
        return $method ;
    }
}
