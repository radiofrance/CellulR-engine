<?php

namespace Rf\WebComponent\EngineBundle\Twig;

use Rf\WebComponent\EngineBundle\Utils\UtilsTrait;
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
     * ViewObjectExtension Constructor.
     *
     * @param FragmentHandler $handler       A FragmentHandler instance
     * @param string          $kernelRootDir
     * @param string          $wcDir
     * @param string          $viewObjectDir
     */
    public function __construct(FragmentHandler $handler, $kernelRootDir, $wcDir, $viewObjectDir)
    {
        $this->handler = $handler;
        $this->kernelRootDir = $kernelRootDir;
        $this->wcDir = $wcDir;
        $this->viewObjectDir = $viewObjectDir;
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

        $files = $this->getViewObjectFile($name);
        if (0 === $files->count()) {
            throw new \Exception(sprintf('The view object file with the name \'%s\' was not found.', $name));
        }

        foreach ($files as $file) {
            $filename = preg_replace('/(.*)\.php$/', '$1', str_replace('/', '\\', $file->getFilename()));
            $namespace = $this->getNamespaceFromDir($this->kernelRootDir, $file->getPath());
        }

        return $this->handler->render(new ControllerReference(sprintf('%s\\%s::__invoke', $namespace, $filename), $attributes), $strategy, $options);
    }

    /**
     * Get the View Object file.
     *
     * @param $name
     *
     * @return Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    public function getViewObjectFile($name)
    {
        $finder = new Finder();

        if (!empty($this->viewObjectDir)) {
            $files = $finder->in(array($this->viewObjectDir))->files()->name(sprintf('%s.php', $name));

            if (0 < $files->count()) {
                return $files;
            }
        }

        return $finder->in(array($this->wcDir))->files()->name(sprintf('%s.php', $name));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'web_component';
    }
}
