<?php

namespace Tests\AppBundle\Utils;

use AppBundle\Utils\SudokuFileMapper;

/**
 * Description of SudokuGridMapperTest
 *
 * @author haclong
 */
class SudokuFileMapperTest extends \PHPUnit_Framework_TestCase 
{
    public function testMapToString()
    {
        $expectedString = '<?php'."\r\n"
                . '$array[0][0] = 2 ;'."\r\n"
                . '$array[0][3] = 1 ;'."\r\n"
                . '$array[1][2] = 1 ;'."\r\n"
                . '$array[3][2] = 3 ;'."\r\n"
                . 'return $array ;' ;
        $array = array() ;
        $array[0][0] = 2 ;
        $array[0][3] = 1 ;
        $array[1][2] = 1 ;
        $array[3][2] = 3 ;
        
        $mapper = new SudokuFileMapper() ;
        $string = $mapper->mapToString($array) ;
        $this->assertEquals($expectedString, $string) ;
    }
}
