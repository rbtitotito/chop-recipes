<?php

require(__DIR__.'/../../vendor/autoload.php');

class RecipesTest extends PHPUnit_Framework_TestCase
{
  protected $client;
  protected $base_uri;
  protected $recipe_id;

  protected function setUp()
  {
    $this->base_uri = $base_uri = 'http://localhost:8080';
    $this->client = new GuzzleHttp\Client([
      'base_uri' => $base_uri,
      'http_errors' => false,
      'stream' => false
      ]);
  }

  public function testGet_ValidInput_RecipeList()
  {
    $this->createNewRecipeHelper();
    $this->createNewRecipeHelper();
    $this->createNewRecipeHelper();
    $this->createNewRecipeHelper();
    $response = $this->client->get($this->base_uri);
    $this->assertEquals(200, $response->getStatusCode());
    $this->assertEquals('application/json', $response->getHeader('Content-Type')[0]);
    $data = $response->getBody();
    var_dump($data);
    $this->assertArrayHasKey($this->_recipe_id, $data);
    // $this->assertArrayHasKey('steps', $data['00001']);
    // $this->assertArrayHasKey('name', $data['00001']);
  }

  public function testGet_ValidInput_RecipeObject()
  {
    $this->createNewRecipeHelper();
    $response = $this->client->get($this->_recipe_id);
    $this->assertEquals(200, $response->getStatusCode());
    $data = json_decode( $response->getBody(), true );
    $this->assertArrayHasKey('recipe_id', $data);
    $this->assertArrayHasKey('steps', $data);
    $this->assertArrayHasKey('name', $data);
  }

  private function createNewRecipeHelper()
  {
    $this->_recipe_id = uniqid();

    $response = $this->client->post('/', [
        'form_params' => [
          'recipe_id' => $this->_recipe_id,
          'name'     => $this->_recipe_id,
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
    return $response;
  }

  public function testPost_NewRecipe_RecipeObject()
  {
    $response = $this->createNewRecipeHelper();
    $this->assertEquals(201, $response->getStatusCode());
    $data = json_decode($response->getBody(), true);
  }

  public function testDelete_Ok()
  {
    $this->createNewRecipeHelper();
    $response = $this->client->delete('/'.$this->_recipe_id);
    $this->assertEquals(200, $response->getStatusCode());
  }

  public function testDelete_Error()
  {
    $response = $this->client->delete('/random-book', ['http-errors' => false]);
    $this->assertEquals(404, $response->getStatusCode());
  }
}
