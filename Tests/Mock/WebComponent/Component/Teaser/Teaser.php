<?php

namespace Rf\WebComponent\EngineBundle\Tests\Mock\WebComponent\Component\Teaser;

/**
 * Class Teaser.
 */
class Teaser
{
    /**
     * @return array The array of data
     */
    public function __invoke($title)
    {
        return array(
            'title' => $title,
            'description' => 'Base content',
        );
    }
}
