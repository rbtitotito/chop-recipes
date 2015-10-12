<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
// Our web handlers
// Return json_encoded recipes as default
$app->get('/', function() use($app, $recipes) {
  $app['monolog']->addDebug('logging output.');
  return $app->json($recipes, 200);
});

$app->post('/', function(Silex\Application $app, Request $request) use($recipes) {
  $app['monolog']->addDebug('logging output.');
  $id = $request->get('recipe_id');
  $name = $request->get('name');
  $recipes[ $id ] = array( 'recipe_id' => $id, 'name' => $name );
  return $app->json( $recipes[$id], 201);
});

//return this recipes details
$app->get('/{recipe_id}', function(Silex\Application $app, $recipe_id) use($recipes) {
  $app['monolog']->addDebug('logging output.');
  if( !isset( $recipes[$recipe_id]))
  {
   return $app->abort(404, "Recipe id {$recipe_id} does not exist.");
  }
  else {
    return $app->json($recipes[$recipe_id],200);
  }
});

$app->delete('/{recipe_id}', function(Silex\Application $app, $recipe_id) use(&$recipes) {
    $app['monolog']->addDebug('logging output.');
    echo "***\n\n {$recipe_id} \n\n";
    if(!isset($recipes[$recipe_id]))
    {
      return $app->abort(404, "Recipe id {$recipe_id} does not exist.");
    }
    else {
      $deleted = $recipes[$recipe_id];
      unset($recipes[$recipe_id]);
      $deleted['deleted'] = true;
      return $app->json($deleted, 200);
    }
  });

return $app;
