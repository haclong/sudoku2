<?php

namespace Tests\AppBundle\Utils;

use AppBundle\Utils\JsonMapper;

/**
 * Description of JsonGetterTest
 *
 * @author haclong
 */
class JsonMapperTest extends \PHPUnit_Framework_TestCase {
    public function testToArray()
    {
        $expected = array() ;
        $expected[0][1] = 3 ;
        $expected[2][1] = 2 ;
        $expected[3][0] = 2 ;
        $expected[3][3] = 1 ;

        $array = array() ;
        $array['grid'] = array() ;
        $array['grid']['size'] = 4 ;
        $array['grid']['tiles'] = array() ;
        $array['grid']['tiles'][] = array('id' => 't.0.1', 'value' => 3) ;
        $array['grid']['tiles'][] = array('id' => 't.2.1', 'value' => 2) ;
        $array['grid']['tiles'][] = array('id' => 't.3.0', 'value' => 2) ;
        $array['grid']['tiles'][] = array('id' => 't.3.3', 'value' => 1) ;
        
        $json = json_encode($array) ;

        $mapper = new JsonMapper() ;
        $responseArray = $mapper->toArray($json) ;
        
        $this->assertTrue(is_array($responseArray)) ;
        $this->assertEquals(4, $responseArray['size']) ;
        $this->assertEquals($expected, $responseArray['tiles']) ;
    }
}
