<?php

namespace Rf\WebComponent\EngineBundle\ViewObject;

class Response
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var int
     */
    protected $maxAge;

    /**
     * @var int
     */
    protected $sharedMaxAge;

    /**
     * Response constructor.
     *
     * @param array $data
     * @param int   $maxAge
     * @param int   $sharedMaxAge
     */
    public function __construct(array $data, $maxAge = null, $sharedMaxAge = null)
    {
        $this->data = $data;
        $this->maxAge = $maxAge;
        $this->sharedMaxAge = $sharedMaxAge;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxAge()
    {
        return $this->maxAge;
    }

    /**
     * @param int $maxAge
     *
     * @return Response
     */
    public function setMaxAge($maxAge)
    {
        $this->maxAge = $maxAge;

        return $this;
    }

    /**
     * @return int
     */
    public function getSharedMaxAge()
    {
        return $this->sharedMaxAge;
    }

    /**
     * @param int $sharedMaxAge
     *
     * @return Response
     */
    public function setSharedMaxAge($sharedMaxAge)
    {
        $this->sharedMaxAge = $sharedMaxAge;

        return $this;
    }
}
