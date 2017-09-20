<?php

$loader = require __DIR__.'/../vendor/autoload.php';

Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

require_once __DIR__.'/mocked-app/AppKernel.php';

