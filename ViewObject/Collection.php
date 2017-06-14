<?php

namespace Rf\WebComponent\EngineBundle\ViewObject;

class Collection
{
    protected $viewObjects = [];

    public function all()
    {
        return $this->viewObjects;
    }

    public function has($name)
    {
        return isset($this->viewObjects[$name]);
    }

    /**
     * @return mixed
     */
    public function getViewObjects($name)
    {
        if (!$this->has($name)) {
            throw new \InvalidArgumentException(sprintf('The viewObject "%s" not found', $name));
        }

        return $this->viewObjects[$name];
    }

    /**
     * @param mixed $viewObjects
     */
    public function setViewObjects($viewObjects)
    {
        $this->viewObjects = $viewObjects;

        return $this;
    }

    public function addViewObject($viewObjectsName, $viewObjects)
    {
        $this->viewObjects[$viewObjectsName] = $viewObjects;

        return $this;
    }
}
