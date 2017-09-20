<?php

namespace Rf\CellulR\EngineBundle\Tests;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as Mother;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class WebTestCase extends Mother
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    protected function setUp()
    {
        parent::setUp();

        static::$kernel = new \CellEngineAppKernel('test', true);
        static::$kernel->boot();
        $this->container = static::$kernel->getContainer();
    }
}
