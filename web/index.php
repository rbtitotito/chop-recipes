<?php

require( __DIR__.'/../vendor/autoload.php');

use Silex\Application;
use Silex\Provider\MonologServiceProvider;

$app = new Application();

// set to false in prodution environment
$app['debug'] = true;

// Simple data store for RAD
$recipes = array(
  '00001' => array(
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
    'name' => 'Whipped Cream',
    'steps' => array(
      '1' => 'Get out Mixer',
      '2' => 'Put in Cream, Sugar and Vanilla',
      '3' => 'Mix until stiff peaks',
      '4' => 'Chill for 10 minutes'
    )
  )
);

// Register the monolog logging service
$app->register(new MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Our web handlers
// Return json_encoded recipes as default
$app->get('/', function() use($app, $recipes) {
  $app['monolog']->addDebug('logging output.');
  return json_encode($recipes);
});

//return this recipes details
$app->get('/{recipe_id}', function(Application $app,
 $recipe_id) use($recipes) {
  $app['monolog']->addDebug('logging output.');
  if( !isset( $recipes[$recipe_id]))
  {
   $app->abort(404, "Recipe id {$recipe_id} does not exist.");
  }
  return json_encode($recipes[$recipe_id]);
});

$app->run();
