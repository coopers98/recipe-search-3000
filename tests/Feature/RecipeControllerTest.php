<?php

namespace Tests\Feature;

use App\Models\Recipe;
use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup test data.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create Chocolate Cake recipe
        $chocolateCake = Recipe::factory()->create([
            'name' => 'Chocolate Cake',
            'description' => 'Delicious chocolate cake recipe',
            'author_email' => 'baker@example.com',
            'slug' => 'chocolate-cake'
        ]);

        // Create ingredients
        $flour = Ingredient::factory()->create(['name' => 'flour']);
        $sugar = Ingredient::factory()->create(['name' => 'sugar']);
        $cocoa = Ingredient::factory()->create(['name' => 'cocoa powder']);
        $eggs = Ingredient::factory()->create(['name' => 'eggs']);
        $milk = Ingredient::factory()->create(['name' => 'milk']);

        // Attach ingredients to recipe
        $chocolateCake->ingredients()->attach([
            $flour->id => ['quantity' => '2', 'unit' => 'cups'],
            $sugar->id => ['quantity' => '1', 'unit' => 'cup'],
            $cocoa->id => ['quantity' => '1/2', 'unit' => 'cup'],
            $eggs->id => ['quantity' => '2', 'unit' => ''],
            $milk->id => ['quantity' => '1', 'unit' => 'cup']
        ]);

        // Add steps
        $chocolateCake->steps()->createMany([
            ['step_number' => 1, 'description' => 'Mix dry ingredients'],
            ['step_number' => 2, 'description' => 'Add wet ingredients'],
            ['step_number' => 3, 'description' => 'Bake at 350°F for 30 minutes', 'duration_minutes' => 30]
        ]);

        // Create Vegetable Soup recipe
        $vegetableSoup = Recipe::factory()->create([
            'name' => 'Vegetable Soup',
            'description' => 'Healthy vegetable soup',
            'author_email' => 'chef@example.com',
            'slug' => 'vegetable-soup'
        ]);

        // Create ingredients
        $carrots = Ingredient::factory()->create(['name' => 'carrots']);
        $potatoes = Ingredient::factory()->create(['name' => 'potatoes']);
        $onions = Ingredient::factory()->create(['name' => 'onions']);
        $celery = Ingredient::factory()->create(['name' => 'celery']);
        $broth = Ingredient::factory()->create(['name' => 'vegetable broth']);

        // Attach ingredients to recipe
        $vegetableSoup->ingredients()->attach([
            $carrots->id => ['quantity' => '2', 'unit' => '', 'preparation' => 'chopped'],
            $potatoes->id => ['quantity' => '3', 'unit' => '', 'preparation' => 'diced'],
            $onions->id => ['quantity' => '1', 'unit' => '', 'preparation' => 'diced'],
            $celery->id => ['quantity' => '2', 'unit' => 'stalks', 'preparation' => 'chopped'],
            $broth->id => ['quantity' => '4', 'unit' => 'cups', 'preparation' => '']
        ]);

        // Add steps
        $vegetableSoup->steps()->createMany([
            ['step_number' => 1, 'description' => 'Chop vegetables', 'duration_minutes' => 10],
            ['step_number' => 2, 'description' => 'Sauté onions', 'duration_minutes' => 5],
            ['step_number' => 3, 'description' => 'Add vegetables and broth'],
            ['step_number' => 4, 'description' => 'Simmer for 20 minutes', 'duration_minutes' => 20]
        ]);

        // Create Potato Salad recipe
        $potatoSalad = Recipe::factory()->create([
            'name' => 'Potato Salad',
            'description' => 'Classic potato salad',
            'author_email' => 'chef@example.com',
            'slug' => 'potato-salad'
        ]);

        // Create ingredients
        $mayonnaise = Ingredient::factory()->create(['name' => 'mayonnaise']);
        $mustard = Ingredient::factory()->create(['name' => 'mustard']);

        // Attach ingredients to recipe
        $potatoSalad->ingredients()->attach([
            $potatoes->id => ['quantity' => '4', 'unit' => 'large', 'preparation' => 'peeled and diced'],
            $mayonnaise->id => ['quantity' => '1/2', 'unit' => 'cup', 'preparation' => ''],
            $mustard->id => ['quantity' => '2', 'unit' => 'tablespoons', 'preparation' => ''],
            $eggs->id => ['quantity' => '2', 'unit' => '', 'preparation' => 'hard-boiled and chopped'],
            $celery->id => ['quantity' => '2', 'unit' => 'stalks', 'preparation' => 'finely chopped']
        ]);

        // Add steps
        $potatoSalad->steps()->createMany([
            ['step_number' => 1, 'description' => 'Boil potatoes', 'duration_minutes' => 20],
            ['step_number' => 2, 'description' => 'Mix dressing ingredients'],
            ['step_number' => 3, 'description' => 'Combine and chill', 'duration_minutes' => 60]
        ]);
    }

    /**
     * Test retrieving all recipes.
     */
    public function test_index_returns_all_recipes_when_no_filters(): void
    {
        $response = $this->getJson('/api/recipes');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data')
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'name',
                             'description',
                             'ingredients',
                             'steps',
                             'author_email',
                             'slug',
                             'created_at',
                             'updated_at'
                         ]
                     ],
                     'meta' => [
                         'current_page',
                         'last_page',
                         'per_page',
                         'total'
                     ]
                 ]);
    }

    /**
     * Test filtering recipes by author email.
     */
    public function test_index_filters_by_author_email(): void
    {
        $response = $this->getJson('/api/recipes?author_email=chef@example.com');

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data')
                 ->assertJsonPath('data.0.author_email', 'chef@example.com')
                 ->assertJsonPath('data.1.author_email', 'chef@example.com');
    }

    /**
     * Test filtering recipes by keyword.
     */
    public function test_index_filters_by_keyword(): void
    {
        // Test keyword in name
        $response = $this->getJson('/api/recipes?keyword=Chocolate');
        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.name', 'Chocolate Cake');

        // Test keyword in description
        $response = $this->getJson('/api/recipes?keyword=Healthy');
        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.name', 'Vegetable Soup');

        // Test keyword in ingredients
        $response = $this->getJson('/api/recipes?keyword=flour');
        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.name', 'Chocolate Cake');

        // Test keyword in steps
        $response = $this->getJson('/api/recipes?keyword=Simmer');
        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.name', 'Vegetable Soup');
    }

    /**
     * Test filtering recipes by ingredient.
     */
    public function test_index_filters_by_ingredient(): void
    {
        $response = $this->getJson('/api/recipes?ingredient=potato');

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');

        $names = collect($response->json('data'))->pluck('name')->toArray();
        $this->assertContains('Vegetable Soup', $names);
        $this->assertContains('Potato Salad', $names);
    }

    /**
     * Test combining multiple filters.
     */
    public function test_index_combines_multiple_filters(): void
    {
        $response = $this->getJson('/api/recipes?author_email=chef@example.com&ingredient=potato');

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');

        $names = collect($response->json('data'))->pluck('name')->toArray();
        $this->assertContains('Vegetable Soup', $names);
        $this->assertContains('Potato Salad', $names);
    }

    /**
     * Test retrieving a specific recipe by slug.
     */
    public function test_show_returns_recipe_by_slug(): void
    {
        // Create a recipe with a specific slug for testing
        $recipe = \App\Models\Recipe::factory()->create([
            'name' => 'Chocolate Cake Test',
            'slug' => 'chocolate-cake-test'
        ]);

        $response = $this->getJson('/api/recipes/chocolate-cake-test');
        $response->assertStatus(200);

        // Debug the response
        $responseData = $response->json();
        $this->assertIsArray($responseData);

        // Use assertJsonPath instead of direct array access
        $response->assertJsonPath('data.name', 'Chocolate Cake Test')
                 ->assertJsonPath('data.slug', 'chocolate-cake-test');
    }

    /**
     * Test retrieving a non-existent recipe.
     */
    public function test_show_returns_404_for_nonexistent_recipe(): void
    {
        $response = $this->getJson('/api/recipes/nonexistent-recipe');

        $response->assertStatus(404);
    }
}
