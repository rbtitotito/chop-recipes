<?php

require(__DIR__.'/../../vendor/autoload.php');

class RecipesTest extends PHPUnit_Framework_TestCase
{
  protected $client;

  protected function setUp()
  {
    $this->client = new GuzzleHttp\Client([
      'base_url' => 'http://localhost:8080',
      'defaults' => ['exceptions' => false]
      ]);
  }

  public function testGet_ValidInput_RecipeObject()
  {
    $response = $this->client->get('/', [
      'query' => [
        'recipe_id' => '0001'
        ]
      ]);
    $this->assertEquals(200, $response->getStatusCode());

    $data = $response->json();
    $this->assertArrayHasKey('recipe_id', $data);
    $this->assertArrayHasKey('steps', $data);
    $this->assertArrayHasKey('name', $data);
  }

  public function testPost_NewRecipe_RecipeObject()
  {
    $recipe_id = uniqid();
    $response = $this->client->post('/', [
        'form_params' => [
          'recipe_id' => $recipe_id,
          'name'     => 'A Random Test',
          'steps'    => [
              [
                'directions' => 'Direction Text 1'
              ],
              [
                'directions' => 'Direction Text 2'
              ],
            ]
          ]
      ]);
    $this->assertEquals(200, $response->getStatusCode());

    $data = $response->json();
    $this->assertEquals($recipe_id, $data['recipe_id']);
  }

  public function testDelete_Error()
  {
    $response = $this->client->delete('/random-book');
    $this->assertEquals(405, $response->getStatusCode());
  }
}
