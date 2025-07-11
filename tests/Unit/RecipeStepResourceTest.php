<?php

namespace Tests\Unit;

use App\Http\Resources\RecipeStepResource;
use App\Models\Recipe;
use App\Models\RecipeStep;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeStepResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_recipe_step_resource_structure()
    {
        $recipe = Recipe::factory()->create();
        $step = RecipeStep::factory()->create([
            'recipe_id' => $recipe->id,
            'step_number' => 1,
            'description' => 'Test step description',
            'duration_minutes' => 15
        ]);

        $resource = new RecipeStepResource($step);
        $array = $resource->toArray(request());

        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('step_number', $array);
        $this->assertArrayHasKey('description', $array);
        $this->assertArrayHasKey('duration_minutes', $array);
        $this->assertArrayHasKey('created_at', $array);
        $this->assertArrayHasKey('updated_at', $array);
        
        $this->assertEquals(1, $array['step_number']);
        $this->assertEquals('Test step description', $array['description']);
        $this->assertEquals(15, $array['duration_minutes']);
    }
}