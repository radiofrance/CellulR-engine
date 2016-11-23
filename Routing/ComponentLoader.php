<?php


namespace Rf\WebComponent\EngineBundle\Routing;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Loader\AnnotationDirectoryLoader;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class ComponentLoader.
 *
 * @author Yoan Guillemin <yoann.guillemin@radiofrance.com>
 */
class ComponentLoader implements LoaderInterface
{
    /**
     * @var boolean
     */
    private $loaded;

    /**
     * @var AnnotationDirectoryLoader
     */
    private $loader;

    /**
     * @var string
     */
    private $componentDir;

    /**
     * @var string
     */
    private $viewObjectDir;

    /**
     * RequestListener constructor.
     *
     * @param AnnotationDirectoryLoader $loader
     * @param string                    $componentDir
     * @param string                    $viewObjectDir
     */
    public function __construct(AnnotationDirectoryLoader $loader, $componentDir, $viewObjectDir)
    {
        $this->loaded = false;
        $this->loader = $loader;
        $this->componentDir = $componentDir;
        $this->viewObjectDir = $viewObjectDir;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add this loader twice');
        }

        $paths[] = $this->componentDir;

        if (!empty($this->viewObjectDir)) {
            $paths[] = $this->viewObjectDir;
        }

        $collection = new RouteCollection();

        foreach ($paths as $path) {
            $routes = $this->loader->load($path, 'annotation');

            foreach ($routes->all() as $name => $route) {
                $collection->add($name, $route);
            }

            foreach ($routes->getResources() as $resource) {
                $collection->addResource($resource);
            }
        }

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return 'component' === $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getResolver()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
    }
}
