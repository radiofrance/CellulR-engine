CellulR Engine
============

CellulR is an engine to build websites. Each page is composed from isolated and independent cells.

This project is production ready, and is used on [www.franceculture.fr](https://www.franceculture.f) .

Each cell is isolated and autonomous. A cell is composed of (at least) one PHP Controller, JavaScript and CSS files.

How it works ?
-----

**1. Rendering**


Render a cell 'VideoPlayer' in current page, with Twig :

```twig
{{ cell('VideoPlayer', {arg1: 'a value'}) }}
```

Or using Varnish ESI (with standalone route):

```twig
{{ cell('VideoPlayer', {arg1: 'a value'}, {strategy: 'esi'}) }}
```

**2. The cell**

```php
<?php

use Rf\CellulR\EngineBundle\CoreObject\Response;

class VideoPlayer
{
    public function __invoke(Video $video) {
        return new Response([
            'video' => $video
        ]); 
    }
}
```

**3. Manifest file**

The `component.json` declares dependencies (with other cells) :

```json
{
    "name": "VideoPlayer",
    "description": "Cell for video player",
    "require": {
        "Legend": "*",
        "Image": "*"
    }
}
```

**4. Assets (Javascript and Less)**

Less files and JavaScript files are automatically included thanks to the [cellulR-builder](https://github.com/radiofrance/CellulR-builder) component.

-----
 
Installation and documentation
-------------

[French only] [Documentation](./Resources/doc/README.md)

Do not hesitate to [help us](https://github.com/radiofrance/CellulR-engine/pulls) to translate documentation in english :)

Licence
-------

Project licensed under Cecill-B license. Please open the LICENSE file.