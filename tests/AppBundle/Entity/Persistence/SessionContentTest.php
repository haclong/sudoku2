<?php

namespace Tests\AppBundle\Entity\Persistence;

use AppBundle\Entity\Persistence\SessionContent;

/**
 * Description of SessionContentTest
 *
 * @author haclong
 */
class SessionContentTest extends \PHPUnit_Framework_TestCase {
    public function testAdd()
    {
        $genmaicha = $this->getMockBuilder('AppBundle\Persistence\IsReadyInterface')->getMock() ;
        $houjicha = $this->getMockBuilder('AppBundle\Persistence\IsReadyInterface')->getMock() ;
        $sencha = $this->getMockBuilder('AppBundle\Persistence\IsReadyInterface')->getMock() ;

        $tile = $this->getMockBuilder('AppBundle\Entity\Event\TileLastPossibility')
                     ->getMock() ;
        $content = new SessionContent() ;
        $content->add($genmaicha) ;
        $content->add($houjicha) ;
        $content->add($sencha) ;
        
        $expected = [$genmaicha, $houjicha, $sencha] ;
        
        $this->assertEquals($expected, $content->getArrayCopy()) ;
    }
}
