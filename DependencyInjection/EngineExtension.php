<?php

namespace Rf\WebComponent\EngineBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class EngineExtension.
 *
 * @author Yoan Guillemin <yoann.guillemin@radiofrance.com>
 */
class EngineExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $configs = $container->getExtensionConfig($this->getAlias());
        $config = $this->processConfiguration(new Configuration(), $configs);
        $kernelRootDir = $container->getParameter('kernel.root_dir');

        $rootDir = $container->getParameterBag()->resolveValue($config['root_dir']);
        $rootDirPrefix = $kernelRootDir.'/../src';
        $rootDirSuffix = trim(str_replace($rootDirPrefix, '', $rootDir), DIRECTORY_SEPARATOR);

        $rootDir = $rootDirPrefix.(!empty($rootDirSuffix) ? DIRECTORY_SEPARATOR.$rootDirSuffix : "");

        if (false === realpath($rootDir) || 0 !== strpos(realpath($rootDir), realpath($rootDirPrefix))) {
            throw new \Exception(sprintf('Usage of non existing directory "%s" in the configuration "root_dir" of "wc_engine".', $config['root_dir']));
        }

        // Init directories parameters
        $container->setParameter('wc.root_dir', $rootDir);
        $container->setParameter('wc.component_dir', $rootDir.'/WebComponent');

        $relativeResource = trim(str_replace($kernelRootDir, '', $rootDir), DIRECTORY_SEPARATOR);

        // Add View Object route
        $directories[] = $relativeResource.'/WebComponent';

        // Add View Object route (override)
        if (is_dir($viewObjectDir = $rootDir.'/ViewObject')) {
            $container->setParameter('wc.view_object_dir', $viewObjectDir);

            $directories[] = $relativeResource.'/ViewObject';
        }

        // Pre-set the DunglasActionBundle
        $this->prependAction($container, $directories);
    }


    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * Pre-set the DunglasActionBundle
     *
     * @param ContainerBuilder $container
     * @param array            $directories
     */
    public function prependAction($container, $directories)
    {
        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['DunglasActionBundle'])) {
            $container->prependExtensionConfig('dunglas_action', array('directories' => $directories));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'wc_engine';
    }
}
