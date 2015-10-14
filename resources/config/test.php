<?php

// include the prod configuration
require __DIR__.'/prod.php';

// enable the debug mode
$app['debug'] = true;

if (isset($app['db.options']['user'])) {
    $app['db.options']['user'] = 'postgres';
}
if (isset($app['db.options']['password'])) {
    $app['db.options']['password'] = '';
}
if (isset($app['db.options']['dbname'])) {
    $db_options['dbname'] = $db_options['dbname'].'_test';
}
