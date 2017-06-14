<?php

namespace Rf\WebComponent\EngineBundle\Twig;

use Rf\WebComponent\EngineBundle\Resolver\ViewObjectResponseResolver;
use Rf\WebComponent\EngineBundle\Finder\Finder;
use Rf\WebComponent\EngineBundle\ViewObject\Collection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;

/**
 * Class WebComponentExtension.
 *
 * @author Yoan Guillemin <yoann.guillemin@radiofrance.com>
 */
class WebComponentExtension extends \Twig_Extension
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
     * ViewObjectExtension Constructor.
     *
     * @param FragmentHandler $handler       A FragmentHandler instance
     * @param string          $kernelRootDir
     * @param string          $wcDir
     * @param string          $viewObjectDir
     */
    public function __construct(Collection $collection, ControllerResolver $controllerResolver, FragmentHandler $handler, Finder $finder)
    {
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
            new \Twig_SimpleFunction('web_component', array($this, 'webComponent'), array('is_safe' => array('html'), 'needs_environment' => true)),
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
     * @throws \Exception When a view object file was not found
     */
    public function webComponent(\Twig_Environment $env, $name, $attributes = array(), $options = array())
    {
        $wcName = $name;
        $strategy = isset($options['strategy']) ? $options['strategy'] : 'inline';
        unset($options['strategy']);

        if (isset($options['vo'])) {
            $name = $options['vo'];
        }

        $file = $this->finder->getData($name, Finder::VIEW_OBJECT);
        if ($file === null) {
            throw new \Exception(sprintf('The view object file with the name \'%s\' was not found.', $name));
        }

        $wcFile = $this->finder->getData($wcName, Finder::WEB_COMPONENT);
        if ($wcFile === null) {
            throw new \Exception(sprintf('The web component file with the name \'%s\' was not found.', $wcName));
        }

        if ($strategy === 'inline') {
            $controller = $this->collection->getViewObjects(strtolower(sprintf('%s\\%s', $file['namespace'], $file['filename'])));

            $request = new Request([], [], $attributes);
            $arguments = $this->controllerResolver->getArguments($request, $controller);
            $result = $data = call_user_func_array($controller, $arguments);

            if ($result instanceof Response) {
                return $data->getContent();
            }

            $response = (new ViewObjectResponseResolver($env))->resolve($wcFile, $result);

            return $response->getContent();
        }

        $attributes = array_merge($attributes, ['_webcomponent' => $wcFile['filename']]);

        return $this->handler->render(new ControllerReference(sprintf('%s\\%s::__invoke', $file['namespace'], $file['filename']), $attributes), $strategy, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'web_component';
    }
}
