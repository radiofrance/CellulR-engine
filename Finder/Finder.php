<?php

namespace Rf\WebComponent\EngineBundle\Finder;

use Rf\WebComponent\EngineBundle\ViewObject\Collection;
use Symfony\Component\Config\ConfigCache;

class Finder
{
    const WEB_COMPONENT = 'wc';
    const VIEW_OBJECT = 'vo';

    /**
     * @var string
     */
    private $wcRootDir;

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
     * @var Collection
     */
    private $collection;

    /**
     * Config constructor.
     *
     * @param string     $wcRootDir
     * @param string     $wcDir
     * @param string     $viewObjectDir
     * @param string     $configCachePath
     * @param Collection $collection
     */
    public function __construct($wcRootDir, $wcDir, $viewObjectDir, $configCachePath, Collection $collection)
    {
        $this->wcRootDir = $wcRootDir;
        $this->wcDir = $wcDir;
        $this->viewObjectDir = $viewObjectDir;
        $this->configCachePath = $configCachePath;
        $this->collection = $collection;
    }

    /**
     * Get the View Object file.
     *
     * @param $name
     * @param string $type
     *
     * @return array
     */
    public function getData($name, $type = self::VIEW_OBJECT)
    {
        $config = $this->getConfig();

        if (isset($config[$name][$type])) {
            return $config[$name][$type];
        }

        return;
    }

    private function getConfig()
    {
        if ($this->config === null && file_exists($this->configCachePath)) {
            $this->config = (array) require $this->configCachePath;
        }

        if (is_array($this->config)) {
            return $this->config;
        }

        $config = [];
        $this->findFiles($config);

        $cache = new ConfigCache($this->configCachePath, true);

        $code = '<?php
return '.var_export($config, true).'
;';
        $cache->write($code);

        return $config;
    }

    private function findFiles(&$config)
    {
        $webComponentPath = realPath($this->wcDir);
        $viewObjectPath = realPath($this->viewObjectDir);

        $voCollection = $this->collection->all();
        foreach ($voCollection as $voName => $vo) {
            $reflector = new \ReflectionClass($vo);
            $fullDirname = dirname($reflector->getFileName());
            $dirname = str_replace(realpath($this->wcRootDir).'/', '', $fullDirname);
            $name = $reflector->getShortName();
            $namespace = $reflector->getNamespaceName();

            if (!isset($config[$name])) {
                $config[$name] = [self::VIEW_OBJECT => [], self::WEB_COMPONENT => []];
            }

            if (strpos($fullDirname, $webComponentPath) !== false) {
                $config[$name][self::WEB_COMPONENT] = ['namespace' => $namespace, 'filename' => $name, 'dirname' => $dirname];
            }

            if (strpos($fullDirname, $webComponentPath) !== false
                && empty($config[$name][self::VIEW_OBJECT])) {
                $config[$name][self::VIEW_OBJECT] = ['namespace' => $namespace, 'filename' => $name, 'dirname' => $dirname];
            }

            if (strpos($fullDirname, $viewObjectPath) !== false) {
                $config[$name][self::VIEW_OBJECT] = ['namespace' => $namespace, 'filename' => $name, 'dirname' => $dirname];
            }

            if (strpos($fullDirname, $viewObjectPath) !== false
                && empty($config[$name][self::WEB_COMPONENT])) {
                $dirname = str_replace([$viewObjectPath, realpath($this->wcRootDir).'/'], [$webComponentPath, ''], $fullDirname);
                $config[$name][self::WEB_COMPONENT] = ['filename' => $name, 'dirname' => $dirname];
            }
        }
    }
}
