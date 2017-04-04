<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\NewsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;


/**
 * Class ResourceServicesCompilerPass
 * @package Positibe\Bundle\OrmContentBundle\DependencyInjection\Compiler
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class ResourceServicesCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $repositories = [
            'positibe.repository.post_tag',
            'positibe.repository.post_collection',
            'positibe.repository.post',
        ];

        foreach ($repositories as $repository) {
            $container->getDefinition($repository)
                ->addMethodCall(
                    'setRequestStack',
                    [new Reference('request_stack')]
                );
        }
    }

} 