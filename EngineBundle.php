<?php

namespace Rf\WebComponent\EngineBundle;

use Rf\WebComponent\EngineBundle\DependencyInjection\Compiler\AddTwigPathsPass;
use Rf\WebComponent\EngineBundle\DependencyInjection\Compiler\AddViewObjectInCollectionService;
use Rf\WebComponent\EngineBundle\DependencyInjection\EngineExtension;
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
        $container->addCompilerPass(new AddViewObjectInCollectionService(), PassConfig::TYPE_OPTIMIZE);
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
