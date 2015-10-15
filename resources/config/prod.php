<?php

$app['locale'] = 'en';
$app['session.default_locale'] = $app['locale'];

// Cache
$app['cache.path'] = __DIR__ . '/../cache';

// Http cache
$app['http_cache.cache_dir'] = $app['cache.path'] . '/http';

/**
 * This function reads database_url from environment and parses correctly
 * for database connect string (heroku setup)
 **/
function parse_db_url() {
  $ret = array();
  $parsed_url = parse_url(getenv("DATABASE_URL"));
  $ret['dbname'] = (isset($parsed_url['path'])) ? substr($parsed_url['path'], 1) : 'chop_shop';
  
  if (isset($parsed_url['pass'])) {
    $ret['password'] = $parsed_url['pass'];
  }
  
  if (isset($parsed_url['user'])) {
    $ret['user'] = $parsed_url['user'];
  }
  
  return $ret;
}

// Doctrine (db)
$db_options = parse_db_url();
$db_options['driver'] = 'pdo_pgsql';
$app['db.options'] = $db_options;
