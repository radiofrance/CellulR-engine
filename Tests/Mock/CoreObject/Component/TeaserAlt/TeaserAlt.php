<?php

namespace Rf\CellulR\EngineBundle\Tests\Mock\CoreObject\Component\TeaserAlt;

/**
 * Class Teaser.
 */
class TeaserAlt
{
    /**
     * @return array The array of data
     */
    public function __invoke($title)
    {
        return array(
            'title' => $title,
            'description' => 'Alternative content',
        );
    }
}
