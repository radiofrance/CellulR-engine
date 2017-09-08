<?php

namespace Rf\CellulR\EngineBundle\Finder;

use Rf\CellulR\EngineBundle\CoreObject\Collection;
use Symfony\Component\Config\ConfigCache;

class Finder
{
    const CELL = 'cell';
    const CORE_OBJECT = 'co';

    /**
     * @var string
     */
    private $cellRootDir;

    /**
     * @var string
     */
    private $cellDir;

    /**
     * @var string
     */
    private $coreObjectDir;

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
     * @param string     $cellRootDir
     * @param string     $cellDir
     * @param string     $coreObjectDir
     * @param string     $configCachePath
     * @param Collection $collection
     */
    public function __construct($cellRootDir, $cellDir, $coreObjectDir, $configCachePath, Collection $collection)
    {
        $this->cellRootDir = $cellRootDir;
        $this->cellDir = $cellDir;
        $this->coreObjectDir = $coreObjectDir;
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
    public function getData($name, $type = self::CORE_OBJECT)
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
        $cellPath = realPath($this->cellDir);
        $coreObjectPath = realPath($this->coreObjectDir);

        $voCollection = $this->collection->all();
        foreach ($voCollection as $voName => $vo) {
            $reflector = new \ReflectionClass($vo);
            $fullDirname = dirname($reflector->getFileName());
            $dirname = str_replace(realpath($this->cellRootDir).'/', '', $fullDirname);
            $name = $reflector->getShortName();
            $namespace = $reflector->getNamespaceName();

            if (!isset($config[$name])) {
                $config[$name] = [self::CORE_OBJECT => [], self::CELL => []];
            }

            if (strpos($fullDirname, $cellPath) !== false) {
                $config[$name][self::CELL] = ['namespace' => $namespace, 'filename' => $name, 'dirname' => $dirname];
            }

            if (strpos($fullDirname, $cellPath) !== false
                && empty($config[$name][self::CORE_OBJECT])) {
                $config[$name][self::CORE_OBJECT] = ['namespace' => $namespace, 'filename' => $name, 'dirname' => $dirname];
            }

            if (strpos($fullDirname, $coreObjectPath) !== false) {
                $config[$name][self::CORE_OBJECT] = ['namespace' => $namespace, 'filename' => $name, 'dirname' => $dirname];
            }

            if (strpos($fullDirname, $coreObjectPath) !== false
                && empty($config[$name][self::CELL])) {
                $dirname = str_replace([$coreObjectPath, realpath($this->cellRootDir).'/'], [$cellPath, ''], $fullDirname);
                $config[$name][self::CELL] = ['filename' => $name, 'dirname' => $dirname];
            }
        }
    }
}
