<?php

namespace Rf\CellulR\EngineBundle\Twig;

use Rf\CellulR\EngineBundle\CoreObject\Collection;
use Rf\CellulR\EngineBundle\Finder\Finder;
use Rf\CellulR\EngineBundle\Resolver\CoreObjectResponseResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;
use Twig_Environment;

/**
 * Class CellulRExtension.
 *
 * @author Yoan Guillemin <yoann.guillemin@radiofrance.com>
 */
class CellulRExtension extends \Twig_Extension
{
    /**
     * @var FragmentHandler
     */
    private $handler;

    /**
     * @var Finder
     */
    private $finder;

    /**
     * @var Collection
     */
    private $collection;

    /**
     * @var ControllerResolver
     */
    private $controllerResolver;

    /**
     * CoreObjectExtension Constructor.
     *
     * @param FragmentHandler $handler       A FragmentHandler instance
     * @param string          $kernelRootDir
     * @param string          $cellulrDir
     * @param string          $coreObjectDir
     */
    public function __construct(
        Collection $collection,
        ControllerResolverInterface $controllerResolver,
        FragmentHandler $handler,
        Finder $finder
    ) {
        $this->collection = $collection;
        $this->controllerResolver = $controllerResolver;
        $this->handler = $handler;
        $this->finder = $finder;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('cell', array($this, 'cell'), array('is_safe' => array('html'), 'needs_environment' => true)),
        );
    }

    /**
     * Render the Web Component given its name.
     *
     * @param string $name
     * @param array  $attributes
     * @param array  $options
     *
     * @return null|string
     *
     * @throws \Exception When a core object file was not found
     */
    public function cell(\Twig_Environment $env, $name, $attributes = array(), $options = array())
    {
        $cellName = $name;
        $strategy = isset($options['strategy']) ? $options['strategy'] : 'inline';
        unset($options['strategy']);

        if (isset($options['co'])) {
            $name = $options['co'];
        }

        $file = $this->finder->getData($name, Finder::CORE_OBJECT);
        if ($file === null) {
            throw new \Exception(sprintf('The core object file with the name \'%s\' was not found.', $name));
        }

        $cellFile = $this->finder->getData($cellName, Finder::CELL);
        if ($cellFile === null) {
            throw new \Exception(sprintf('The cell file with the name \'%s\' was not found.', $cellName));
        }

        if ($strategy === 'inline') {
            $controller = $this->collection->getCoreObjects(strtolower(sprintf('%s\\%s', $file['namespace'], $file['filename'])));

            $request = new Request([], [], $attributes);
            $arguments = $this->controllerResolver->getArguments($request, $controller);
            $result = $data = call_user_func_array($controller, $arguments);

            if ($result instanceof Response) {
                return $data->getContent();
            }

            $response = (new CoreObjectResponseResolver($env))->resolve($cellFile, $result);

            return $response->getContent();
        }

        $attributes = array_merge($attributes, ['_cell' => $cellFile['filename']]);

        return $this->handler->render(new ControllerReference(sprintf('%s\\%s::__invoke', $file['namespace'], $file['filename']), $attributes), $strategy, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'cell';
    }
}
