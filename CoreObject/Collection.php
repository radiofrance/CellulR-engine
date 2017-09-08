<?php

namespace Rf\CellulR\EngineBundle\CoreObject;

class Collection
{
    protected $coreObjects = [];

    public function all()
    {
        return $this->coreObjects;
    }

    public function has($name)
    {
        return isset($this->coreObjects[$name]);
    }

    /**
     * @return mixed
     */
    public function getCoreObjects($name)
    {
        if (!$this->has($name)) {
            throw new \InvalidArgumentException(sprintf('The coreObject "%s" not found', $name));
        }

        return $this->coreObjects[$name];
    }

    /**
     * @param mixed $coreObjects
     */
    public function setCoreObjects($coreObjects)
    {
        $this->coreObjects = $coreObjects;

        return $this;
    }

    public function addCoreObject($coreObjectsName, $coreObjects)
    {
        $this->coreObjects[$coreObjectsName] = $coreObjects;

        return $this;
    }
}
