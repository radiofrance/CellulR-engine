<?php

namespace Rf\CellulR\EngineBundle\Tests\Mock\CoreObject\Page\Simple;

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
