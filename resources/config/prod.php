<?php

$app['locale'] = 'en';
$app['session.default_locale'] = $app['locale'];

// Cache
$app['cache.path'] = __DIR__ . '/../cache';

// Http cache
$app['http_cache.cache_dir'] = $app['cache.path'] . '/http';

// Doctrine (db)
$app['db.options'] = array(
    'driver'   => 'pdo_mysql',
    'host'     => 'localhost',
    'dbname'   => 'chop_shopper',
    'user'     => 'root',
    'password' => '',
);
