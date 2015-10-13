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
      'sync' => true
      ]);
  }

  public function testGet_ValidInput_RecipeList()
  {
      // create some random objects to test
      // this should be seeded in fixutres
    $this->createNewRecipeHelper();
    $this->createNewRecipeHelper();
    $this->createNewRecipeHelper();
    $this->createNewRecipeHelper();
    $response = $this->client->get($this->base_uri);
    $this->assertEquals(200, $response->getStatusCode());
    $this->assertEquals('application/json', $response->getHeader('Content-Type')[0]);
    $body = $response->getBody();
    $data = json_decode($body, true);
    $last_item = $data[sizeof($data)-1];
    $this->assertArrayHasKey('name', $last_item);

    // Search for last known recipe id somewhwere in list
    // $found = false;
    // foreach ($data as $item) {
    //     echo $item['name'];
    //     if ($item['name'] == $this->recipe_id) {
    //         $found = true;
    //     }
    // }
    // $this->assertTrue($found);
  }

  public function testGet_ValidInput_RecipeObject()
  {
    $new_response = $this->createNewRecipeHelper();
    $new_recipe = json_decode( $new_response->getBody(), true );
    $response = $this->client->get('/'.$new_recipe['recipe_id']);
    $this->assertEquals(200, $response->getStatusCode());
    $data = json_decode( $response->getBody(), true );
    $this->assertArrayHasKey('recipe_id', $data);
    $this->assertArrayHasKey('name', $data);
  }

  /**
   * Helper function to create new
   * Recpie object via post
   * with pseudo random id
   */
  private function createNewRecipeHelper()
  {
    $this->recipe_id = uniqid();
    $params = array();
    $params['name'] = $this->recipe_id;
    $params['steps'] = [
            [
                'directions' => 'Mix the eggs and the bacon',
            ],
            [
                'directions' => 'Mix the butter with the buiscit',
            ],
        ];
    $response = $this->client->post($this->base_uri, [
        'form_params' => $params
      ]);
    return $response;
  }

  public function testPost_NewRecipe_RecipeObject()
  {
    $response = $this->createNewRecipeHelper();
    $this->assertEquals(201, $response->getStatusCode());
    $data = json_decode($response->getBody(), true);
    $this->assertArrayHasKey('name', $data);
    $this->assertArrayHasKey('steps',$data);
  }

  private function findRecipeByName($name)
  {
      $response = $this->client->get('/search/'.urlencode($name));
      $body = $response->getBody();
      $data = json_decode($body, true);
      return $data;
  }

  public function testDelete_Ok()
  {
    $this->createNewRecipeHelper();
    $recipe = $this->findRecipeByName($this->recipe_id);
    $response = $this->client->delete('/'.$recipe['recipe_id']);
    $this->assertEquals(200, $response->getStatusCode());
  }

  public function testDelete_Error()
  {
    $response = $this->client->delete('/-11234', ['http-errors' => false]);
    $this->assertEquals(404, $response->getStatusCode());
  }
}
