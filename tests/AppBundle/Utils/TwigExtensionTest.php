<?php


namespace Tests\AppBundle\Utils;
use AppBundle\Utils\TwigExtension;

/**
 * Description of TwigExtensionTest
 *
 * @author haclong
 */
class TwigExtensionTest extends \PHPUnit_Framework_TestCase {
    public function testGetFilters()
    {
        $filter = new TwigExtension() ;
        $this->assertThat(
                $filter->getFilters(),
                $this->containsOnlyInstancesOf('Twig_SimpleFilter')
                ) ;
    }
    
    public function testSqrt()
    {
        $filter = new TwigExtension() ;
        $this->assertEquals(3, $filter->sqrtFilter(9)) ;
    }
    
    public function testGetName()
    {
        $filter = new TwigExtension() ;
        $this->assertEquals('app_extension', $filter->getName()) ;
    }
}
