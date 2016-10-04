<?php

namespace Tests\AppBundle\Utils;

use AppBundle\Entity\Persistence\SessionContent;
use AppBundle\Exception\MissingSessionContentException;
use AppBundle\Utils\SessionCompiler;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Description of SessionCompilerTest
 *
 * @author haclong
 */
class SessionCompilerTest extends \PHPUnit_Framework_TestCase {
    public function testCompilerReturnsMissingSessionContentException() {
        $this->setExpectedException(MissingSessionContentException::class) ;
        $container = new ContainerBuilder();

        $compiler = new SessionCompiler() ;
        $compiler->process($container) ;
    }
    
    public function testCompilerWithoutAnyTaggedService()
    {
        $container = new ContainerBuilder();
        $container->register('sessionContent', '\AppBundle\Entity\Persistence\SessionContent') ;

        $compiler = new SessionCompiler() ;
        $compiler->process($container) ;
        
        $this->assertEquals(0, count($container->get('sessionContent'))) ;
    }
    
    public function testCompilerWithTaggedServices()
    {
        $container = new ContainerBuilder();
        $container->register('sessionContent', '\AppBundle\Entity\Persistence\SessionContent') ;
        $taggedService1 = new Definition('stdClass') ;
        $taggedService1->addTag('session.content') ;
        $container->setDefinition('taggedService1', $taggedService1) ;
        $taggedService2 = new Definition('stdClass') ;
        $taggedService2->addTag('session.content') ;
        $container->setDefinition('taggedService2', $taggedService2) ;

        $compiler = new SessionCompiler() ;
        $compiler->process($container) ;
        
        $expectedArray = new SessionContent() ;
        $expectedArray->offsetSet(null, $container->get('taggedService1')) ;
        $expectedArray->offsetSet(null, $container->get('taggedService2')) ;
        
        $this->assertEquals($expectedArray, $container->get('sessionContent')) ;
    }
}
