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
                ->enumNode('default_rendering')
                    ->values(array('server_side', 'client_side', 'both'))
                    ->defaultValue('both')
                ->end()
                ->arrayNode('serverside_rendering')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('fail_loud')
                            ->defaultFalse()
                    ->end()
                    ->booleanNode('trace')
                        ->defaultFalse()
                    ->end()
                    ->enumNode('mode')
                        ->values(array('phpexecjs', 'external_server'))
                        ->defaultValue('phpexecjs')
                    ->end()
                    ->scalarNode('server_bundle_path')
                        ->defaultNull()
                    ->end()
                    ->scalarNode('server_socket_path')
                        ->defaultNull()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
