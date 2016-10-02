<?php

namespace Tests\AppBundle\Service;

use AppBundle\Exception\AlreadySetTileException;
use AppBundle\Service\GroupsService;
use \Exception ;
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
    
    public function testSetThrowsAlreadySetTileException()
    {
        $this->setExpectedException(AlreadySetTileException::class) ;
        $this->dispatcher->expects($this->never())
                         ->method('dispatch')
                         ->with('settile.validate', $this->equalTo($this->validateTileSetEvent)) ;
        
        $this->groups->method('getRow')
                     ->willReturn($this->onConsecutiveCalls(['0.0', '0.1', '0.2', '0.3'], ['3.0', '3.1', '3.2', '3.3'])) ;
        $this->groups->method('getCol')
                     ->willReturn($this->onConsecutiveCalls(['0.0', '0.1', '0.2', '0.3'], ['1.0', '2.0', '3.0'])) ;
        $this->groups->method('getRegion')
                     ->willReturn($this->onConsecutiveCalls(['0.0', '0.1', '0.2', '0.3'], ['3.0', '3.1', '3.2', '3.3'])) ;
        $service = new GroupsService($this->dispatcher, $this->validateTileSetEvent, $this->deduceTileEvent) ;
        $service->set($this->groups, 1, 0, 0) ;
        $service->set($this->groups, 1, 3, 0) ;
    }
    
    public function testSetDispatchSetTileValidateEvent()
    {
        $impactedTiles = ['0.1', '0.3'] ;
        $valuesByGroup['col'][0][0] = ['0.0', '0.1', '0.2', '0.3'] ;
        $valuesByGroup['col'][0][1] = ['0.0', '0.1', '0.2', '0.3'] ;
        $valuesByGroup['col'][0][2] = ['0.0', '0.1', '0.2', '0.3'] ;
        $valuesByGroup['col'][0][3] = ['0.0', '0.1', '0.2', '0.3'] ;

        $this->dispatcher->expects($this->once())
                         ->method('dispatch')
                         ->with('settile.validate', $this->equalTo($this->validateTileSetEvent)) ;
        
        $this->groups->method('getRow')
                     ->willReturn(['0.0', '0.1', '0.2', '0.3']) ;
        $this->groups->method('getCol')
                     ->willReturn(['0.0', '1.0', '2.0', '3.0']) ;
        $this->groups->method('getRegion')
                     ->willReturn(['0.0', '0.1', '1.0', '1.1']) ;
        $this->groups->method('getImpactedTiles')
                     ->willReturn($impactedTiles) ;
        $this->groups->method('getValuesByGroup')
                    ->willReturn($valuesByGroup) ;
        $this->groups->method('getValuesByTile')
                    ->willReturn(array()) ;
        $service = new GroupsService($this->dispatcher, $this->validateTileSetEvent, $this->deduceTileEvent) ;
        $service->set($this->groups, 1, 0, 0) ;
    }
    
    public function testSetDispatchLastValueInGroup()
    {
        $valuesByGroup['col'][0][0] = ['0.0', '0.1', '0.2', '0.3'] ;
        $valuesByGroup['col'][0][1] = ['0.0', '0.1', '0.2', '0.3'] ;
        $valuesByGroup['col'][0][2] = ['0.3'] ;
        $valuesByGroup['col'][0][3] = ['0.0', '0.1', '0.2', '0.3'] ;
        
        $this->dispatcher->expects($this->at(1))
                         ->method('dispatch')
                         ->with('tile.deduce', $this->equalTo($this->deduceTileEvent)) ;
        
        $this->groups->method('getRow')
                     ->willReturn(['0.0', '0.1', '0.2', '0.3']) ;
        $this->groups->method('getCol')
                     ->willReturn(['0.0', '1.0', '2.0', '3.0']) ;
        $this->groups->method('getRegion')
                     ->willReturn(['0.0', '0.1', '1.0', '1.1']) ;
        $this->groups->method('getImpactedTiles')
                     ->willReturn(array()) ;
        $this->groups->method('getValuesByGroup')
                    ->willReturn($valuesByGroup) ;
        $this->groups->method('getValuesByTile')
                    ->willReturn(array()) ;
        $service = new GroupsService($this->dispatcher, $this->validateTileSetEvent, $this->deduceTileEvent) ;
        $service->set($this->groups, 1, 0, 0) ;
    }
    
    public function testSetDispatchLastValueInTile()
    {
        $valuesByTile['0.0']['col'] = [0] ;
        $valuesByTile['0.0']['row'] = [0] ;
        $valuesByTile['0.0']['region'] = [0] ;
        
        $this->dispatcher->expects($this->at(1))
                         ->method('dispatch')
                         ->with('tile.deduce', $this->equalTo($this->deduceTileEvent)) ;
        
        $this->groups->method('getRow')
                     ->willReturn(['0.0', '0.1', '0.2', '0.3']) ;
        $this->groups->method('getCol')
                     ->willReturn(['0.0', '1.0', '2.0', '3.0']) ;
        $this->groups->method('getRegion')
                     ->willReturn(['0.0', '0.1', '1.0', '1.1']) ;
        $this->groups->method('getImpactedTiles')
                     ->willReturn(array()) ;
        $this->groups->method('getValuesByGroup')
                    ->willReturn(array()) ;
        $this->groups->method('getValuesByTile')
                    ->willReturn($valuesByTile) ;
        $service = new GroupsService($this->dispatcher, $this->validateTileSetEvent, $this->deduceTileEvent) ;
        $service->set($this->groups, 1, 0, 0) ;
    }
    
    public function testSetThrowsExceptionWithDesynchronizedValuesByTile()
    {
        $valuesByTile['0.0']['col'] = [0, 1] ;
        $valuesByTile['0.0']['row'] = [0] ;
        $valuesByTile['0.0']['region'] = [0] ;
        
        $this->setExpectedException(Exception::class) ;
        
        $this->groups->method('getRow')
                     ->willReturn(['0.0', '0.1', '0.2', '0.3']) ;
        $this->groups->method('getCol')
                     ->willReturn(['0.0', '1.0', '2.0', '3.0']) ;
        $this->groups->method('getRegion')
                     ->willReturn(['0.0', '0.1', '1.0', '1.1']) ;
        $this->groups->method('getImpactedTiles')
                     ->willReturn(array()) ;
        $this->groups->method('getValuesByGroup')
                    ->willReturn(array()) ;
        $this->groups->method('getValuesByTile')
                    ->willReturn($valuesByTile) ;
        $service = new GroupsService($this->dispatcher, $this->validateTileSetEvent, $this->deduceTileEvent) ;
        $service->set($this->groups, 1, 0, 0) ;
    }
}
