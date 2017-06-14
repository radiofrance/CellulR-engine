<?php

namespace Rf\WebComponent\EngineBundle\Tests\Mock\ViewObject\Page\Simple;

use Symfony\Component\Routing\Annotation\Route;

/**
 * Class Simple.
 */
class Simple
{
    /**
     * @Route("/simple", name="simple_page_test")
     *
     * @return array The array of data
     */
    public function __invoke()
    {
        return array(
            'title' => 'simple page',
        );
    }
}
