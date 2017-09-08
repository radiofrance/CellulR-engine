<?php

namespace Rf\CellulR\EngineBundle\Tests\App;

use Rf\CellulR\EngineBundle\Tests\WebTestCase;

class CoreObjectCollectionTest extends WebTestCase
{
    public function testCollectionIsFilledByDependencyInjection()
    {
        $collection = $this->container->get('rf.cellulr.engine.co_container');

        $this->assertTrue(
            $collection->has('rf\cellulr\enginebundle\tests\mock\cell\component\teaser\teaser')
        );

        $this->assertInstanceOf(
            'Rf\CellulR\EngineBundle\Tests\Mock\Cell\Component\Teaser\Teaser',
            $collection->getCoreObjects('rf\cellulr\enginebundle\tests\mock\cell\component\teaser\teaser')
        );
    }
}
