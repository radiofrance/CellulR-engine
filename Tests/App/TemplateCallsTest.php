<?php

namespace Rf\CellulR\EngineBundle\Tests\App;

use Rf\CellulR\EngineBundle\Tests\WebTestCase;

class TemplateCallsTest extends WebTestCase
{
    public function testCellCallsInTemplateShouldReturnsItsTemplateContent()
    {
        $twig = $this->container->get('twig');
        $html = $twig->render(sprintf('@base/templates/%s.html.twig', lcfirst(substr(__FUNCTION__, 4))));
        $this->assertEquals(
            '<section><div class="teaser"><h1>Overrided title</h1><p>Base content</p></div></section>',
            $html
        );
    }

    public function testCoreObjectAlternativeCallsInTemplateShouldOverrideContent()
    {
        $twig = $this->container->get('twig');
        $html = $twig->render(sprintf('@base/templates/%s.html.twig', lcfirst(substr(__FUNCTION__, 4))));
        $this->assertEquals(
            '<section><div class="teaser"><h1>Fake title</h1><p>Alternative content</p></div></section>',
            $html
        );
    }
}
