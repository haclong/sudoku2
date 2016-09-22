<?php

namespace Tests\AppBundle\Utils;

use AppBundle\Entity\Tile;
use AppBundle\Entity\Tiles;
use AppBundle\Entity\Tiles\Tileset;
use AppBundle\Utils\TilesMapper;

/**
 * Description of TilesMapperTest
 *
 * @author haclong
 */
class TilesMapperTest extends \PHPUnit_Framework_TestCase {
    public function testToArray()
    {
        $values = $this->getMockBuilder('AppBundle\Entity\Values')
                        ->getMock() ;
        $tileset = new Tileset() ;
        $tiles = new Tiles($tileset) ;
        $tiles->init(9) ;

        $mapper = new TilesMapper() ;
        $array = $mapper->toArray($tiles, $values) ;
        
        $this->assertEquals(81, count($array['tiles'])) ;
    }
}