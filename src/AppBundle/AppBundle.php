<?php

namespace AppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use AppBundle\Utils\SessionCompiler;
use AppBundle\Utils\SudokuEntitiesCompiler;

class AppBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new SessionCompiler());
        $container->addCompilerPass(new SudokuEntitiesCompiler()) ;
    }
}