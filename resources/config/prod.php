<?php

$app['locale'] = 'en';
$app['session.default_locale'] = $app['locale'];

// Cache
$app['cache.path'] = __DIR__ . '/../cache';

// Http cache
$app['http_cache.cache_dir'] = $app['cache.path'] . '/http';

// Doctrine (db)
// Use Sqlite for RAD
$app['db.options'] = array(
    'driver'   => 'pdo_sqlite',
    'path'     => __DIR__.'/app.db',
);
