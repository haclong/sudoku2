<?php

namespace AppBundle\Utils;

use AppBundle\Exception\MissingSudokuEntitiesException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Description of SudokuEntitiesCompiler
 *
 * @author haclong
 */
class SudokuEntitiesCompiler implements CompilerPassInterface {
    public function process(ContainerBuilder $container)
    {
        // always first check if the primary service is defined
        if (!$container->has('sudokuEntities')) {
            throw new MissingSudokuEntitiesException() ;
        }

        $definition = $container->findDefinition('sudokuEntities');

        $taggedServices = $container->findTaggedServiceIds('sudoku.entity');

        foreach ($taggedServices as $id => $tags) {
            // add the transport service to the ChainTransport service
            $definition->addMethodCall('offsetSet', array($id, new Reference($id)));
        }
    }
}
