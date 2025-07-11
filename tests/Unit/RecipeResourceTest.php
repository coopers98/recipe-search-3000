<?php

namespace Tests\Unit;

use App\Http\Resources\RecipeResource;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeStep;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_recipe_resource_structure()
    {
        $recipe = Recipe::factory()->create([
            'name' => 'Test Recipe',
            'description' => 'Test Description',
            'author_email' => 'test@example.com'
        ]);

        $resource = new RecipeResource($recipe);
        $array = $resource->toArray(request());

        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('description', $array);
        $this->assertArrayHasKey('ingredients', $array);
        $this->assertArrayHasKey('steps', $array);
        $this->assertArrayHasKey('author_email', $array);
        $this->assertArrayHasKey('slug', $array);
        $this->assertArrayHasKey('created_at', $array);
        $this->assertArrayHasKey('updated_at', $array);
        
        $this->assertEquals('Test Recipe', $array['name']);
        $this->assertEquals('Test Description', $array['description']);
        $this->assertEquals('test@example.com', $array['author_email']);
    }

    public function test_recipe_resource_with_loaded_relationships()
    {
        $recipe = Recipe::factory()->create([
            'name' => 'Test Recipe'
        ]);

        // Create ingredients and steps
        $ingredient = Ingredient::factory()->create(['name' => 'Test Ingredient']);
        $recipe->ingredients()->attach($ingredient->id, [
            'quantity' => '2',
            'unit' => 'cups',
            'preparation' => 'diced'
        ]);

        RecipeStep::factory()->create([
            'recipe_id' => $recipe->id,
            'step_number' => 1,
            'description' => 'Test step',
            'duration_minutes' => 10
        ]);

        // Load relationships
        $recipe->load(['ingredients', 'steps']);

        $resource = new RecipeResource($recipe);
        $array = $resource->toArray(request());

        // Check ingredients are properly formatted using IngredientResource
        $this->assertInstanceOf(\Illuminate\Http\Resources\Json\AnonymousResourceCollection::class, $array['ingredients']);
        $ingredientsArray = $array['ingredients']->toArray(request());
        $this->assertCount(1, $ingredientsArray);
        $this->assertEquals('Test Ingredient', $ingredientsArray[0]['name']);
        $this->assertEquals('2', $ingredientsArray[0]['quantity']);
        $this->assertEquals('cups', $ingredientsArray[0]['unit']);
        $this->assertEquals('diced', $ingredientsArray[0]['preparation']);

        // Check steps are properly formatted using RecipeStepResource
        $this->assertInstanceOf(\Illuminate\Http\Resources\Json\AnonymousResourceCollection::class, $array['steps']);
        $stepsArray = $array['steps']->toArray(request());
        $this->assertCount(1, $stepsArray);
        $this->assertEquals(1, $stepsArray[0]['step_number']);
        $this->assertEquals('Test step', $stepsArray[0]['description']);
        $this->assertEquals(10, $stepsArray[0]['duration_minutes']);
    }

    public function test_recipe_resource_without_loaded_relationships()
    {
        $recipe = Recipe::factory()->create();

        $resource = new RecipeResource($recipe);
        $array = $resource->toArray(request());

        // When relationships are not loaded, they should be empty arrays
        $this->assertEquals([], $array['ingredients']);
        $this->assertEquals([], $array['steps']);
    }
}