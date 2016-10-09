<?php

namespace Tests\AppBundle\Service;

use AppBundle\Service\SetTileService;

/**
 * Description of SetTileServiceTest
 *
 * @author haclong
 */
class SetTileServiceTest extends \PHPUnit_Framework_TestCase {
    public function testSetTile()
    {
        $dispatcher = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcher')
                                 ->getMock() ;
        $tileset = $this->getMockBuilder('AppBundle\Entity\Event\TileSet')
                        ->getMock() ;
        $event = $this->getMockBuilder('AppBundle\Event\SetTileEvent')
                                   ->setConstructorArgs(array($tileset))
                                   ->getMock() ;
        $event->method('getTile')->willReturn($tileset) ;
        $service = new SetTileService($dispatcher, $event) ;
        
        $dispatcher->expects($this->once())
                         ->method('dispatch')
                         ->with('tile.set', $this->equalTo($event)) ;
        $service->setTile(3, 2, 5) ;
    }
}