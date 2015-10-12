<?php

require(__DIR__.'/../../vendor/autoload.php');

class RecipesTest extends PHPUnit_Framework_TestCase
{
  protected $client;
  protected $base_uri;

  protected function setUp()
  {
    $this->base_uri = $base_uri = 'http://localhost:8080';
    $this->client = new GuzzleHttp\Client([
      'base_uri' => $base_uri,
      'http_errors' => false
      ]);
  }

  public function testGet_ValidInput_RecipeList()
  {
    $response = $this->client->get($this->base_uri);
    $this->assertEquals(200, $response->getStatusCode());
    $this->assertEquals('application/json', $response->getHeader('Content-Type')[0]);
    $data = json_decode( $response->getBody(), true );
    // var_dump( $data );
    $this->assertArrayHasKey('00001', $data);
    $this->assertArrayHasKey('steps', $data['00001']);
    $this->assertArrayHasKey('name', $data['00001']);
  }

  public function testGet_ValidInput_RecipeObject()
  {
    $response = $this->client->get('/00001');
    $this->assertEquals(200, $response->getStatusCode());

    $data = json_decode( $response->getBody(), true );
    // var_dump($data);
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
    $this->assertEquals(201, $response->getStatusCode());

    $data = json_decode($response->getBody(), true);
    $this->assertEquals($recipe_id, $data['recipe_id']);
  }

  public function testDelete_Ok()
  {
    $response = $this->client->delete('/00002');
    $this->assertEquals(200, $response->getStatusCode());
  }

  public function testDelete_Error()
  {
    $response = $this->client->delete('/random-book', ['http-errors' => false]);
    $this->assertEquals(404, $response->getStatusCode());
  }
}
