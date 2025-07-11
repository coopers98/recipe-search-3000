<?php

namespace Tests\Unit;

use App\Http\Resources\IngredientResource;
use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IngredientResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_ingredient_resource_structure()
    {
        $ingredient = Ingredient::factory()->create([
            'name' => 'Test Ingredient'
        ]);

        $resource = new IngredientResource($ingredient);
        $array = $resource->toArray(request());

        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('created_at', $array);
        $this->assertArrayHasKey('updated_at', $array);
        $this->assertEquals('Test Ingredient', $array['name']);
    }

    public function test_ingredient_resource_with_pivot_data()
    {
        $recipe = Recipe::factory()->create();
        $ingredient = Ingredient::factory()->create([
            'name' => 'Test Ingredient'
        ]);

        // Attach ingredient to recipe with pivot data
        $recipe->ingredients()->attach($ingredient->id, [
            'quantity' => '2',
            'unit' => 'cups',
            'preparation' => 'diced'
        ]);

        // Load the ingredient with pivot data
        $ingredientWithPivot = $recipe->ingredients()->first();
        
        $resource = new IngredientResource($ingredientWithPivot);
        $array = $resource->toArray(request());

        $this->assertEquals('2', $array['quantity']);
        $this->assertEquals('cups', $array['unit']);
        $this->assertEquals('diced', $array['preparation']);
    }
}