<?php

namespace Rf\CellulR\EngineBundle\Resolver;

use Rf\CellulR\EngineBundle\Utils\UtilsTrait;
use Symfony\Component\HttpFoundation\Response;
use Rf\CellulR\EngineBundle\CoreObject\Response as COResponse;

class CoreObjectResponseResolver
{
    use UtilsTrait;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * CoreObjectResponseResolver constructor.
     *
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function resolve($cellData, $data)
    {
        if (!isset($cellData['filename']) || !isset($cellData['dirname'])) {
            throw new \InvalidArgumentException('Keys "filename" & "dirname" are mandatory from the cell data');
        }

        if ($data instanceof COResponse) {
            $data = $data->getData();
        }

        $path = str_replace(['CoreObject', 'Cell'], '', $cellData['dirname']);

        return new Response(
            $this->twig->render(
                sprintf('@cellulr/%s/%s.html.twig', $path, $this->fromCamelCase($cellData['filename'])),
                $data
            )
        );
    }
}
