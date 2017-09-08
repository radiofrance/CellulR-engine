<?php

namespace Rf\CellulR\EngineBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration.
 *
 * @author Yoan Guillemin <yoann.guillemin@radiofrance.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('cellulr_engine')
            ->children()
                ->scalarNode('root_dir')
                ->info('The cellulR root directory. Relative to %kernel.root_dir%/../src')
                ->defaultValue('%kernel.root_dir%/../src')
            ->end()
        ;

        return $treeBuilder;
    }
}
