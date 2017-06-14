<?php

namespace Rf\WebComponent\EngineBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AddViewObjectInCollectionService implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $definitions = $container->getDefinitions();
        $voDefinitions = $container->getDefinition('rf.wc.engine.vo_container');

        foreach ($definitions as $definitionName => $definition) {
            if (strpos($definitionName, 'webcomponent\\page') === false
                && strpos($definitionName, 'webcomponent\\component') === false
                && strpos($definitionName, 'viewobject\\page') === false
                && strpos($definitionName, 'viewobject\\component') === false) {
                continue;
            }

            $voDefinitions->addMethodCall('addViewObject', [$definitionName, $definition]);
        }
    }
}
