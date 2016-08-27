<?php
namespace Tests\AppBundle\Utils;

use AppBundle\Entity\Grid;
use AppBundle\Utils\GridMapper;


/**
 * Description of GridMapperTest
 *
 * @author haclong
 */
class GridMapperTest  extends \PHPUnit_Framework_TestCase 
{
    public function testGridToArrayMapper()
    {
        $tiles = array() ;
        $tiles[0][1] = 3 ;
        $tiles[2][1] = 2 ;
        $tiles[3][0] = 2 ;
        $tiles[3][3] = 1 ;
        $grid = new Grid() ;
        $grid->init(4) ;
        $grid->setTiles($tiles) ;
        
        $expectedArray = array() ;
        $expectedArray['size'] = 4 ;
        $expectedArray['tiles'] = array() ;
        $expectedArray['tiles'][] = array('id' => 't.0.1', 'value' => 3) ;
        $expectedArray['tiles'][] = array('id' => 't.2.1', 'value' => 2) ;
        $expectedArray['tiles'][] = array('id' => 't.3.0', 'value' => 2) ;
        $expectedArray['tiles'][] = array('id' => 't.3.3', 'value' => 1) ;
        
        $mapper = new GridMapper() ;
        $array = $mapper->toArray($grid) ;
        
        $this->assertEquals(count($array['tiles']), 4) ;
        $this->assertArrayHasKey(3, $tiles[3]) ;
        $this->assertEquals($expectedArray, $array) ;
    }
}
