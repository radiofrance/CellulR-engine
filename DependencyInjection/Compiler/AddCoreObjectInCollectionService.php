<?php

namespace Rf\CellulR\EngineBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AddCoreObjectInCollectionService implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $definitions = $container->getDefinitions();
        $voDefinitions = $container->getDefinition('rf.cellulr.engine.co_container');

        foreach ($definitions as $definitionName => $definition) {
            if (strpos($definitionName, 'cell\\page') === false
                && strpos($definitionName, 'cell\\component') === false
                && strpos($definitionName, 'coreobject\\page') === false
                && strpos($definitionName, 'coreobject\\component') === false) {
                continue;
            }

            $voDefinitions->addMethodCall('addCoreObject', [$definitionName, $definition]);
        }
    }
}
