<?php

$app['debug'] = true;
if (isset($app['db.options']['dbname'])) {
    $db_options['dbname'] = $db_options['dbname'].'_test';
}
