<?php

$app['locale'] = 'en';
$app['session.default_locale'] = $app['locale'];

// Cache
$app['cache.path'] = __DIR__ . '/../cache';

// Http cache
$app['http_cache.cache_dir'] = $app['cache.path'] . '/http';

// Doctrine (db)
$db_options = parse_url(getenv("DATABASE_URL"));
$db_options['driver'] = 'pdo_pgsql';
$db_options['dbname'] = 'chop_shop';
$db_options['password'] = $db_options['pass'];
$app['db.options'] = $db_options;
