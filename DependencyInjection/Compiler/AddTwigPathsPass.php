<?php

namespace Rf\WebComponent\EngineBundle\DependencyInjection\Compiler;

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

        if (!$container->hasParameter('wc.component_dir')) {
            return;
        }

        $componentDir = $container->getParameterBag()->resolveValue('%wc.component_dir%');

        if (is_dir($componentDir)) {
            $twigFilesystemLoaderDefinition->addMethodCall('addPath', array($componentDir, 'wc'));
        }

        if (is_dir($componentDir.'/Page')) {
            $twigFilesystemLoaderDefinition->addMethodCall('addPath', array("$componentDir/Page", 'wc_page'));
        }

        if (is_dir($componentDir.'/Component')) {
            $twigFilesystemLoaderDefinition->addMethodCall('addPath', array("$componentDir/Component", 'wc_component'));
        }
    }

}