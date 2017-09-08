<?php

namespace Rf\CellulR\EngineBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class AddTwigPathsPass.
 *
 * @author Yoan Guillemin <yoann.guillemin@radiofrance.com>
 */
class AddTwigPathsPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('twig.loader.filesystem')) {
            return;
        }

        // Inject paths for twig
        $twigFilesystemLoaderDefinition = $container->getDefinition('twig.loader.filesystem');

        if (!$container->hasParameter('cellulr.component_dir')) {
            return;
        }

        $componentDir = $container->getParameterBag()->resolveValue('%cellulr.component_dir%');

        if (is_dir($componentDir)) {
            $twigFilesystemLoaderDefinition->addMethodCall('addPath', array($componentDir, 'cellulr'));
        }

        if (is_dir($componentDir.'/Page')) {
            $twigFilesystemLoaderDefinition->addMethodCall('addPath', array("$componentDir/Page", 'cellulr_page'));
        }

        if (is_dir($componentDir.'/Component')) {
            $twigFilesystemLoaderDefinition->addMethodCall('addPath', array("$componentDir/Component", 'cellulr_component'));
        }
    }

}