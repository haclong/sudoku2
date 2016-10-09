<?php

namespace Tests\AppBundle\Utils;

use AppBundle\Entity\Event\SudokuEntities;
use AppBundle\Exception\MissingSudokuEntitiesException;
use AppBundle\Utils\SudokuEntitiesCompiler;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Description of SudokuEntitiesCompilerTest
 *
 * @author haclong
 */
class SudokuEntitiesCompilerTest extends \PHPUnit_Framework_TestCase {
    public function testCompilerReturnsMissingSessionContentException() {
        $this->setExpectedException(MissingSudokuEntitiesException::class) ;
        $container = new ContainerBuilder();

        $compiler = new SudokuEntitiesCompiler() ;
        $compiler->process($container) ;
    }

    public function testCompilerWithoutAnyTaggedService()
    {
        $container = new ContainerBuilder();
        $container->register('sudokuEntities', '\AppBundle\Entity\Event\SudokuEntities') ;

        $compiler = new SudokuEntitiesCompiler()  ;
        $compiler->process($container) ;
        
        $this->assertEquals(0, count($container->get('sudokuEntities'))) ;
    }

    public function testCompilerWithTaggedServices()
    {
        $container = new ContainerBuilder();
        $container->register('sudokuEntities', '\AppBundle\Entity\Event\SudokuEntities') ;
        $taggedService1 = new Definition('stdClass') ;
        $taggedService1->addTag('sudoku.entity') ;
        $container->setDefinition('taggedService1', $taggedService1) ;
        $taggedService2 = new Definition('stdClass') ;
        $taggedService2->addTag('sudoku.entity') ;
        $container->setDefinition('taggedService2', $taggedService2) ;

        $compiler = new SudokuEntitiesCompiler() ;
        $compiler->process($container) ;
        
        $expectedArray = new SudokuEntities() ;
        $expectedArray->offsetSet('taggedservice1', $container->get('taggedService1')) ;
        $expectedArray->offsetSet('taggedservice2', $container->get('taggedService2')) ;
        
        $this->assertEquals($expectedArray, $container->get('sudokuEntities')) ;
    }
}