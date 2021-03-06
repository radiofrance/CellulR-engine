<?php

namespace Rf\CellulR\EngineBundle\Tests\App;

use Rf\CellulR\EngineBundle\Tests\WebTestCase;

class CoreObjectAsControllerTest extends WebTestCase
{
    public function testASimplePageMustReturnsItsContent()
    {
        $client = $this->container->get('test.client');
        $client->request('GET', '/simple');
        $this->assertEquals(
            '<!DOCTYPE html><html lang="fr"><head></head><body><header><h1>simple page</h1></header><main><p>Simple page</p></main></body></html>',
            $client->getResponse()->getContent()
        );
    }

    public function testAComposedPageMustReturnsItsContentAndCellContentWith()
    {
        $client = $this->container->get('test.client');
        $client->request('GET', '/composed');
        $this->assertEquals(
            '<!DOCTYPE html><html lang="fr"><head></head><body><header><h1>composed page</h1></header><main><div class="teaser"><h1>Teaser basic</h1><p>Base content</p></div><div class="teaser"><h1>Teaser alternative</h1><p>Alternative content</p></div></main></body></html>',
            $client->getResponse()->getContent()
        );
    }
}
