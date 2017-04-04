<?php

namespace Positibe\Bundle\NewsBundle;

use Positibe\Bundle\NewsBundle\DependencyInjection\Compiler\ResourceServicesCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PositibeNewsBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ResourceServicesCompilerPass());
    }
}
