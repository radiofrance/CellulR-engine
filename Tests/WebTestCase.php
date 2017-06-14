<?php

namespace Rf\WebComponent\EngineBundle\Tests;

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
        $loader = require __DIR__.'/../../../autoload.php';
        AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

        require_once __DIR__.'/mocked-app/AppKernel.php';
        static::$kernel = new \WebComponentEngineAppKernel('test', true);
        static::$kernel->boot();
        $this->container = static::$kernel->getContainer();
    }
}
