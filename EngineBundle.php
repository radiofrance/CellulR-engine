<?php

namespace Rf\CellulR\EngineBundle;

use Rf\CellulR\EngineBundle\DependencyInjection\Compiler\AddTwigPathsPass;
use Rf\CellulR\EngineBundle\DependencyInjection\Compiler\AddCoreObjectInCollectionService;
use Rf\CellulR\EngineBundle\DependencyInjection\EngineExtension;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class EngineBundle.
 *
 * @author Yoan Guillemin <yoann.guillemin@radiofrance.com>
 */
class EngineBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AddTwigPathsPass(), PassConfig::TYPE_OPTIMIZE);
        $container->addCompilerPass(new AddCoreObjectInCollectionService(), PassConfig::TYPE_OPTIMIZE);
    }

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new EngineExtension();
        }

        return $this->extension;
    }
}
