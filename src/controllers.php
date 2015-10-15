<?php

use ChopShopper\Entity\Recipe;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Our web handlers
// Return json_encoded recipes as default
// @todo check request type hint for output
// @todo support xml|json|html
// @todo responde with rel links for possible action with objects
// @todo add data validation
// @todo move to Class/OO Controllers

/**
 * Get Recipes List
 */
$app->get('/recipes/', function () use ($app) {

    $app['monolog']->addDebug('Logging output / Get Recipes');
    $em = $app['orm.em'];
    $recipes = $em->getRepository('ChopShopper\Entity\Recipe')
                ->createQueryBuilder('r')
                ->select('r')
                ->getQuery()
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

    $app['monolog']->addDebug('recipes: '. var_export($recipes, true));
    $app['monolog']->addDebug('logging output End / Get Recipes');

    return $app->json($recipes, 200);
});

/**
 * Create New Recipe
 */
$app->post('/recipes/', function (Silex\Application $app, Request $request) {

    $app['monolog']->addDebug('logging output / Post Recipe');
    $body = $request->getContent();
    $recipe_obj = json_decode($body);
    $em = $app['orm.em'];
    $recipe = $em->getRepository('ChopShopper\Entity\Recipe')
        ->createRecipe($recipe_obj);

    $em = $app['orm.em'];
    $em->persist($recipe);
    $em->flush();

    $app['monolog']->addDebug('logging output end / Post Recipe');

    return $app->json($recipe->toArray(), 201);
});

/**
 * Get recipe details by id
 */
$app->get('/recipes/{recipe_id}', function (Silex\Application $app, $recipe_id) {

    $app['monolog']->addDebug('logging output / Get Recipe');
    $em = $app['orm.em'];
    $recipe = $em->getRepository('ChopShopper\Entity\Recipe')->find($recipe_id);

    if (!$recipe) {
        $app->abort(404, "Recipe id {$recipe_id} does not exist.");
    }

    $app['monolog']->addDebug('logging output end / Get Recipe');

    return $app->json($recipe->toArray(), 200);
});

/**
 * Search by name
 * @todo fuzzy search with result list of possbilities and weight
 */
$app->get('/recipes/search/{name}', function (Silex\Application $app, $name) {

    $app['monolog']->addDebug('logging output / Search by Recipe');
    $app['monolog']->addDebug('name: '.$name);
    $em = $app['orm.em'];
    $recipe = $em->getRepository('ChopShopper\Entity\Recipe')->findOneBy(array('name'=>$name));
    if (!$recipe) {
        $app->abort(404, "Recipe named {$name} does not exist.");
    }

    $app['monolog']->addDebug('recipe: '.print_r($recipe->toArray(), true));
    $app['monolog']->addDebug('logging output end / Get Recipe');

    return $app->json($recipe->toArray(), 200);
});

/**
 * Delete recipe
 * @todo authorization
 */
$app->delete('/recipes/{recipe_id}', function (Silex\Application $app, $recipe_id) {

    $app['monolog']->addDebug('logging output / Delete Recipe');
    $em = $app['orm.em'];
    $recipe = $em->getRepository('ChopShopper\Entity\Recipe')->find($recipe_id);

    if (!$recipe) {
        $app->abort(404, "Recipe id {$recipe_id} does not exist.");
    }

  // refactor this
    foreach ($recipe->getSteps() as $step) {
        foreach ($step->getStepIngredients() as $step_ingredient) {
            $em->remove($step_ingredient);
        }

        $em->remove($step);
    }

    $em->remove($recipe);
    $em->flush();

  // set deleted flag for http code compliance (imho)
    $recipe->setDeleted(true);

    $app['monolog']->addDebug('logging output end / Delete Recipe');

    return $app->json($recipe->toArray(), 200);
});

/**
 * Error handling
 */
$app->error(function (\Exception $e, $code) use ($app) {

    // skip if debug
    if ($app['debug']) {
        return;
    }

    switch ($code) {
        case 404:
            $message = 'The requested resource code not be found';
            break;
        case 405:
            $message = 'Validation exception';
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
    }

    return new Response($message, $code);
});

return $app;
