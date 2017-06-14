<?php

namespace Rf\WebComponent\EngineBundle\Resolver;

use Rf\WebComponent\EngineBundle\Utils\UtilsTrait;
use Symfony\Component\HttpFoundation\Response;
use Rf\WebComponent\EngineBundle\ViewObject\Response as VOResponse;

class ViewObjectResponseResolver
{
    use UtilsTrait;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * ViewObjectResponseResolver constructor.
     *
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function resolve($webcomponentData, $data)
    {
        if (!isset($webcomponentData['filename']) || !isset($webcomponentData['dirname'])) {
            throw new \InvalidArgumentException('Keys "filename" & "dirname" are mandatory from the webComponent data');
        }

        if ($data instanceof VOResponse) {
            $data = $data->getData();
        }

        $path = str_replace(['ViewObject', 'WebComponent'], '', $webcomponentData['dirname']);

        return new Response(
            $this->twig->render(
                sprintf('@wc/%s/%s.html.twig', $path, $this->fromCamelCase($webcomponentData['filename'])),
                $data
            )
        );
    }
}
