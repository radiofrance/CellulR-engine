<?php

namespace Rf\WebComponent\EngineBundle\Tests\Mock\ViewObject\Component\TeaserAlt;

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
