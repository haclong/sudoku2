<?php

namespace Tests\AppBundle\Utils;

use AppBundle\Entity\Persistence\SessionContent;
use AppBundle\Exception\MissingSessionContentException;
use AppBundle\Utils\SessionCompiler;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

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
        $container->register('session', 'Symfony\Component\HttpFoundation\Session\Session') ;
        $taggedService1 = new Definition('\AppBundle\Persistence\GridSession') ;
        $taggedService1->addTag('session.content') ;
        $taggedService1->addArgument(new Reference('session')) ;
        $container->setDefinition('taggedService1', $taggedService1) ;
        $taggedService2 = new Definition('\AppBundle\Persistence\TilesSession') ;
        $taggedService2->addTag('session.content') ;
        $taggedService2->addArgument(new Reference('session')) ;
        $container->setDefinition('taggedService2', $taggedService2) ;

        $compiler = new SessionCompiler() ;
        $compiler->process($container) ;
        
        $expectedArray = new SessionContent() ;
        $expectedArray->offsetSet(null, $container->get('taggedService1')) ;
        $expectedArray->offsetSet(null, $container->get('taggedService2')) ;
        
        $this->assertEquals($expectedArray, $container->get('sessionContent')) ;
    }
}
