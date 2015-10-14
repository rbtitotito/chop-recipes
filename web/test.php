<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
// set to false in prodution environment
$app['debug'] = true;

require __DIR__.'/../resources/config/test.php';
require __DIR__.'/../src/app.php';

require __DIR__.'/../src/controllers.php';

$app['http_cache']->run();
