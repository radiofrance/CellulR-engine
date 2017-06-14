<?php

namespace Rf\WebComponent\EngineBundle\Tests\App;

use Rf\WebComponent\EngineBundle\Tests\WebTestCase;

class ViewObjectCollectionTest extends WebTestCase
{
    public function testCollectionIsFilledByDependencyInjection()
    {
        $collection = $this->container->get('rf.wc.engine.vo_container');

        $this->assertTrue(
            $collection->has('rf\webcomponent\enginebundle\tests\mock\webcomponent\component\teaser\teaser')
        );

        $this->assertInstanceOf(
            'Rf\WebComponent\EngineBundle\Tests\Mock\WebComponent\Component\Teaser\Teaser',
            $collection->getViewObjects('rf\webcomponent\enginebundle\tests\mock\webcomponent\component\teaser\teaser')
        );
    }
}
