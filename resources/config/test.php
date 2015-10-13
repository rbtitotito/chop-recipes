<?php

$app['debug'] = true;
$app['db.options']['user'] = 'postgres';
$app['db.options']['password'] = '';
if (isset($app['db.options']['dbname'])) {
    $db_options['dbname'] = $db_options['dbname'].'_test';
}
