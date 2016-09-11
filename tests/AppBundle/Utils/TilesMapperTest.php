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
        $tileset = new Tileset() ;
        $tile = new Tile() ;
        $tiles = new Tiles($tileset, $tile) ;
        $tiles->setTileset(9) ;

        $mapper = new TilesMapper() ;
        $array = $mapper->toArray($tiles) ;
        
        $this->assertEquals(81, count($array['tiles'])) ;
    }
}