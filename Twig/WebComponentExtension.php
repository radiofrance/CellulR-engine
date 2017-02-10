<?php

namespace Rf\WebComponent\EngineBundle\Twig;

use Rf\WebComponent\EngineBundle\Utils\UtilsTrait;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;

/**
 * Class WebComponentExtension.
 *
 * @author Yoan Guillemin <yoann.guillemin@radiofrance.com>
 */
class WebComponentExtension extends \Twig_Extension
{
    use UtilsTrait;

    /**
     * @var FragmentHandler
     */
    private $handler;

    /**
     * @var string
     */
    private $kernelRootDir;

    /**
     * @var string
     */
    private $wcDir;

    /**
     * @var string
     */
    private $viewObjectDir;

    /**
     * @var string
     */
    private $configCachePath;

    /**
     * @var array|null
     */
    private $config;

    /**
     * ViewObjectExtension Constructor.
     *
     * @param FragmentHandler $handler       A FragmentHandler instance
     * @param string          $kernelRootDir
     * @param string          $wcDir
     * @param string          $viewObjectDir
     */
    public function __construct(FragmentHandler $handler, $kernelRootDir, $wcDir, $viewObjectDir, $configCachePath)
    {
        $this->handler = $handler;
        $this->kernelRootDir = $kernelRootDir;
        $this->wcDir = $wcDir;
        $this->viewObjectDir = $viewObjectDir;
        $this->configCachePath = $configCachePath;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('web_component', array($this, 'webComponent'), array('is_safe' => array('html'))),
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
    public function webComponent($name, $attributes = array(), $options = array())
    {
        $strategy = isset($options['strategy']) ? $options['strategy'] : 'inline';
        unset($options['strategy']);

        $file = $this->getViewObjectFile($name);
        if ($file === null) {
            throw new \Exception(sprintf('The view object file with the name \'%s\' was not found.', $name));
        }

        return $this->handler->render(new ControllerReference(sprintf('%s\\%s::__invoke', $file['namespace'], $file['filename']), $attributes), $strategy, $options);
    }

    /**
     * Get the View Object file.
     *
     * @param $name
     *
     * @return array
     */
    public function getViewObjectFile($name)
    {
        $config = $this->getConfigViewObject();

        if (isset($config[$name])) {
            return $config[$name];
        }

        return;
    }

    private function getConfigViewObject()
    {
        if ($this->config === null && file_exists($this->configCachePath)) {
            $this->config = (array) require $this->configCachePath;
        }

        if (is_array($this->config)) {
            return $this->config;
        }

        $config = [];
        $this->findViewObjectFiles($config, $this->wcDir);
        $this->findViewObjectFiles($config, $this->viewObjectDir);

        $cache = new ConfigCache($this->configCachePath, true);

        $code = '<?php
return '.var_export($config, true).'
;';
        $cache->write($code);

        return $config;
    }

    private function findViewObjectFiles(&$config, $folderPath)
    {
        $finder = new Finder();
        $directories = $finder->depth('< 2')->in(array($folderPath))->directories();

        $finderFile = new Finder();
        $files = [];

        foreach ($directories as $viewObjectName) {
            $name = $viewObjectName->getBasename();
            $files = $finderFile->in(array($viewObjectName->getRealPath()))->files()->name($name.'.php');
        }

        foreach ($files as $file) {
            $name = $file->getBasename('.'.$file->getExtension());

            $filename = preg_replace('/(.*)\.php$/', '$1', str_replace('/', '\\', $file->getFilename()));
            $namespace = $this->getNamespaceFromDir(realpath($this->kernelRootDir.'/../src'), $file->getPath());

            $config[$name] = ['namespace' => $namespace, 'filename' => $filename];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'web_component';
    }
}
