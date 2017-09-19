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
            $name = mb_strtolower($definitionName);
            if (strpos($name, 'cell\\page') === false
                && strpos($name, 'cell\\component') === false
                && strpos($name, 'coreobject\\page') === false
                && strpos($name, 'coreobject\\component') === false) {
                continue;
            }

            $voDefinitions->addMethodCall('addCoreObject', [$name, $definition]);
        }
    }
}
