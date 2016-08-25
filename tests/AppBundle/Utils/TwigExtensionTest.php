<?php


namespace Tests\AppBundle\Utils;
use AppBundle\Utils\TwigExtension;

/**
 * Description of TwigExtensionTest
 *
 * @author haclong
 */
class TwigExtensionTest extends \PHPUnit_Framework_TestCase {
    public function testSqrt()
    {
        $filter = new TwigExtension() ;
        $this->assertEquals(3, $filter->sqrtFilter(9)) ;
    }
}
