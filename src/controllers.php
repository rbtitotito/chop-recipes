<?php

use ChopShopper\Entity\Recipe;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Our web handlers
// Return json_encoded recipes as default

$app->get('/', function() use($app)
{
  $app['monolog']->addDebug('logging output.');
  $em = $app['orm.em'];
  $recipes = $em->getRepository('ChopShopper\Entity\Recipe')
                ->createQueryBuilder('r')
                ->select('r')
                ->getQuery()
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
  return $app->json($recipes, 200);
});

$app->post('/', function(Silex\Application $app, Request $request)
{
  $app['monolog']->addDebug('logging output.');
  $name = $request->get('name');
  $em = $app['orm.em'];
  $recipe = false;

  $recipe = new Recipe();

  if (!$recipe) {
    $recipe->setName($name);
    $em->persist($recipe);
    $em->flush();
  }

  return $app->json( $recipe->toArray(), 201);
});

//return this recipes details
$app->get('/{recipe_id}', function(Silex\Application $app, $recipe_id)
{
  $app['monolog']->addDebug('logging output.');
  $em = $app['orm.em'];
  $recipe = $em->getRepository('ChopShopper\Entity\Recipe')->find($recipe_id);

  if (!$recipe) {
    $app->abort(404, "Recipe id {$recipe_id} does not exist.");
  }

  return $app->json($recipe->toArray(),200);
});

$app->delete('/{recipe_id}', function(Silex\Application $app, $recipe_id)
{
  $app['monolog']->addDebug('logging output.');
  $em = $app['orm.em'];
  $recipe = $em->getRepository('ChopShopper\Entity\Recipe')->find($recipe_id);

  if (!$recipe) {
    $app->abort(404, "Recipe id {$recipe_id} does not exist.");
  }

  $em->remove($recipe);
  $em->flush();

  // set deleted flag for http code compliance (imho)
  $recipe->setDeleted(true);

  return $app->json($recipe->toArray(), 200);
});

$app->error(function (\Exception $e, $code) use ($app)
{
  switch ($code) {
      case 404:
          $message = 'The requested page could not be found.';
          break;
      default:
          $message = 'We are sorry, but something went terribly wrong.';
  }

  return new Response($message, $code);
});

return $app;
