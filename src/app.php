<?php
/**
  * Register Service Providers
  */
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\MonologServiceProvider;

$app->register(new HttpCacheServiceProvider());

// Application log file
$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../resources/log/app.log',
    'monolog.name'    => 'app',
    'monolog.level'   => 300 // = Logger::WARNING
));

$app->register(new Silex\Provider\DoctrineServiceProvider());

// $dbopts = parse_url(getenv('DATABASE_URL'));
// $app->register(new Herrera\Pdo\PdoServiceProvider(),
//   array(
//     'pdo.dsn' => 'pgsql:dbname='.ltrim($dbopts['path'],'/').'host='.$dbopts['host'],
//     'pdo.port' => $dbopts['port'],
//     'pdo.username' => $dbopts["user"],
//     'pdo.password' => $dbopts["pass"]
//   )
// );

// Simple data store for RAD
$recipes = array(
  '00001' => array(
    'recipe_id' => '00001',
    'name' => 'Arroz con Pollo',
    'steps' => array(
      '1' => 'Prep Ingredients',
      '2' => 'Turn on Stove',
      '3' => 'Cook Rice',
      '4' => 'Roast Chicken',
      '5' => 'Combine and season'
    )
  ),
  '00002' => array(
    'recipe_id' => '00002',
    'name' => 'Whipped Cream',
    'steps' => array(
      '1' => 'Get out Mixer',
      '2' => 'Put in Cream, Sugar and Vanilla',
      '3' => 'Mix until stiff peaks',
      '4' => 'Chill for 10 minutes'
    )
  )
);

return $app;
