<?php

namespace Tests\AppBundle\Utils;

use AppBundle\Utils\GridMapper;

/**
 * Description of GridMapperTest
 *
 * @author haclong
 */
class GridMapperTest  extends \PHPUnit_Framework_TestCase 
{
    public function testFromJson()
    {
        $mapper = new GridMapper() ;
        $json = '{"grid":{"size":4,"tiles":[{"id":"t.0.0","value":""},{"id":"t.0.1","value":""},{"id":"t.0.2","value":"2"},{"id":"t.0.3","value":""},{"id":"t.1.0","value":"3"},{"id":"t.1.1","value":""},{"id":"t.1.2","value":"1"},{"id":"t.1.3","value":""},{"id":"t.2.0","value":""},{"id":"t.2.1","value":"4"},{"id":"t.2.2","value":"3"},{"id":"t.2.3","value":"2"},{"id":"t.3.0","value":""},{"id":"t.3.1","value":"1"},{"id":"t.3.2","value":""},{"id":"t.3.3","value":""}]}}' ;
        $grid = $mapper->fromJson($json) ;
        $tiles = $grid->getTiles() ;
        $this->assertInstanceOf('AppBundle\Entity\Grid', $grid) ;
        $this->assertEquals($grid->getSize(), 4) ;
        $this->assertEquals(count($tiles), 4) ;
        $this->assertArrayHasKey(3, $tiles[3]) ;
        $this->assertEquals($tiles[1][2], 1) ;
    }
}
