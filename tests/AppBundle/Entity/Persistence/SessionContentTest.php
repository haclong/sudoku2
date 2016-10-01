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
        $content = new SessionContent() ;
        $content->add('genmaicha') ;
        $content->add('houjicha') ;
        $content->add('sencha') ;
        
        $expected = ['genmaicha', 'houjicha', 'sencha'] ;
        
        $this->assertEquals($expected, $content->getArrayCopy()) ;
    }
}
