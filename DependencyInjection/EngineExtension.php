<?php

namespace Rf\CellulR\EngineBundle\DependencyInjection;

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

        if (false === realpath($rootDir)) {
            throw new \Exception(sprintf('Usage of non existing directory "%s" in the configuration "root_dir" of "cellulr_engine".', $config['root_dir']));
        }

        if (false === realpath($rootDir.'/Cell')) {
            throw new \Exception(sprintf('Directory "%s/Cell" not exists".', $rootDir));
        }

        // Init directories parameters
        $container->setParameter('cellulr.root_dir', $rootDir);
        $container->setParameter('cellulr.component_dir', $rootDir.'/Cell');

        $relativeResource = trim(str_replace($kernelRootDir, '', $rootDir), DIRECTORY_SEPARATOR);

        // Add View Object route
        $directories[] = $relativeResource.'/Cell';

        // Add View Object route (override)
        if (is_dir($coreObjectDir = $rootDir.'/CoreObject')) {
            $container->setParameter('cellulr.core_object_dir', $coreObjectDir);

            $directories[] = $relativeResource.'/CoreObject';
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
     * Pre-set the DunglasActionBundle.
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
        return 'cellulr_engine';
    }
}
