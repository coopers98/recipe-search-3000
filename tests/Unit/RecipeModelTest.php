<?php

namespace Tests\Unit;

use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the slug is automatically generated.
     */
    public function test_slug_is_automatically_generated(): void
    {
        $recipe = Recipe::factory()->create([
            'name' => 'Test Recipe Name',
            'slug' => null // Force slug generation
        ]);

        $this->assertEquals('test-recipe-name', $recipe->slug);
    }

    /**
     * Test that duplicate slugs are made unique.
     */
    public function test_duplicate_slugs_are_made_unique(): void
    {
        // Create first recipe
        $recipe1 = Recipe::factory()->create([
            'name' => 'Test Recipe',
            'slug' => null // Force slug generation
        ]);

        // Create second recipe with same name
        $recipe2 = Recipe::factory()->create([
            'name' => 'Test Recipe',
            'slug' => null // Force slug generation
        ]);

        $this->assertEquals('test-recipe', $recipe1->slug);
        $this->assertEquals('test-recipe-1', $recipe2->slug);
    }

    /**
     * Test the byAuthorEmail scope.
     */
    public function test_by_author_email_scope(): void
    {
        // Create test recipes
        Recipe::factory()->create([
            'author_email' => 'author1@example.com'
        ]);

        Recipe::factory()->create([
            'author_email' => 'author2@example.com'
        ]);

        Recipe::factory()->create([
            'author_email' => 'author1@example.com'
        ]);

        // Test the scope
        $recipes = Recipe::byAuthorEmail('author1@example.com')->get();

        $this->assertCount(2, $recipes);
        $this->assertEquals('author1@example.com', $recipes[0]->author_email);
        $this->assertEquals('author1@example.com', $recipes[1]->author_email);
    }

    /**
     * Test the byKeyword scope.
     */
    public function test_by_keyword_scope(): void
    {
        // Create a recipe with specific name and description
        $chocolateCake = Recipe::factory()
            ->create([
                'name' => 'Chocolate Cake',
                'description' => 'Delicious dessert'
            ]);

        // Add ingredients and steps
        $flour = \App\Models\Ingredient::factory()->create(['name' => 'flour']);
        $sugar = \App\Models\Ingredient::factory()->create(['name' => 'sugar']);
        $cocoa = \App\Models\Ingredient::factory()->create(['name' => 'cocoa']);

        $chocolateCake->ingredients()->attach([
            $flour->id => ['quantity' => '2', 'unit' => 'cups', 'preparation' => ''],
            $sugar->id => ['quantity' => '1', 'unit' => 'cup', 'preparation' => ''],
            $cocoa->id => ['quantity' => '1/2', 'unit' => 'cup', 'preparation' => '']
        ]);

        $chocolateCake->steps()->createMany([
            ['step_number' => 1, 'description' => 'Mix dry ingredients'],
            ['step_number' => 2, 'description' => 'Bake at 350°F for 30 minutes']
        ]);

        // Create another recipe
        $vanillaPudding = Recipe::factory()
            ->create([
                'name' => 'Vanilla Pudding',
                'description' => 'Simple pudding recipe'
            ]);

        // Add ingredients and steps
        $milk = \App\Models\Ingredient::factory()->create(['name' => 'milk']);
        $vanilla = \App\Models\Ingredient::factory()->create(['name' => 'vanilla']);

        $vanillaPudding->ingredients()->attach([
            $milk->id => ['quantity' => '2', 'unit' => 'cups'],
            $sugar->id => ['quantity' => '1/2', 'unit' => 'cup'],
            $vanilla->id => ['quantity' => '1', 'unit' => 'teaspoon']
        ]);

        $vanillaPudding->steps()->createMany([
            ['step_number' => 1, 'description' => 'Heat milk in a saucepan'],
            ['step_number' => 2, 'description' => 'Add ingredients and stir'],
            ['step_number' => 3, 'description' => 'Chill for 2 hours']
        ]);

        // Test keyword in name
        $recipes = Recipe::byKeyword('Chocolate')->get();
        $this->assertCount(1, $recipes);
        $this->assertEquals('Chocolate Cake', $recipes[0]->name);

        // Test keyword in description
        $recipes = Recipe::byKeyword('Simple')->get();
        $this->assertCount(1, $recipes);
        $this->assertEquals('Vanilla Pudding', $recipes[0]->name);

        // Test keyword in ingredients
        $recipes = Recipe::byKeyword('vanilla')->get();
        $this->assertCount(1, $recipes);
        $this->assertEquals('Vanilla Pudding', $recipes[0]->name);

        // Test keyword in steps
        $recipes = Recipe::byKeyword('Bake')->get();
        $this->assertCount(1, $recipes);
        $this->assertEquals('Chocolate Cake', $recipes[0]->name);
    }

    /**
     * Test the byIngredient scope.
     */
    public function test_by_ingredient_scope(): void
    {
        // Create common ingredients
        $flour = \App\Models\Ingredient::factory()->create(['name' => 'flour']);
        $sugar = \App\Models\Ingredient::factory()->create(['name' => 'sugar']);
        $cocoa = \App\Models\Ingredient::factory()->create(['name' => 'cocoa']);
        $butter = \App\Models\Ingredient::factory()->create(['name' => 'butter']);

        // Create first recipe
        $chocolateCake = Recipe::factory()
            ->create([
                'name' => 'Chocolate Cake',
                'description' => 'Delicious dessert'
            ]);

        $chocolateCake->ingredients()->attach([
            $flour->id => ['quantity' => '2', 'unit' => 'cups', 'preparation' => ''],
            $sugar->id => ['quantity' => '1', 'unit' => 'cup', 'preparation' => ''],
            $cocoa->id => ['quantity' => '1/2', 'unit' => 'cup', 'preparation' => '']
        ]);

        // Create second recipe
        $sugarCookies = Recipe::factory()
            ->create([
                'name' => 'Sugar Cookies',
                'description' => 'Simple cookie recipe'
            ]);

        $sugarCookies->ingredients()->attach([
            $flour->id => ['quantity' => '2', 'unit' => 'cups', 'preparation' => ''],
            $sugar->id => ['quantity' => '1', 'unit' => 'cup', 'preparation' => ''],
            $butter->id => ['quantity' => '1/2', 'unit' => 'cup', 'preparation' => '']
        ]);

        // Test ingredient search
        $recipes = Recipe::byIngredient('flour')->get();
        $this->assertCount(2, $recipes);

        $recipes = Recipe::byIngredient('cocoa')->get();
        $this->assertCount(1, $recipes);
        $this->assertEquals('Chocolate Cake', $recipes[0]->name);

        $recipes = Recipe::byIngredient('butter')->get();
        $this->assertCount(1, $recipes);
        $this->assertEquals('Sugar Cookies', $recipes[0]->name);
    }

    /**
     * Test the relationships between Recipe, Ingredient, and RecipeStep.
     */
    public function test_relationships(): void
    {
        // Create a recipe with relationships using the factory
        $recipe = Recipe::factory()->create();

        // Create ingredients and attach to recipe
        $flour = \App\Models\Ingredient::factory()->create(['name' => 'flour']);
        $sugar = \App\Models\Ingredient::factory()->create(['name' => 'sugar']);

        $recipe->ingredients()->attach($flour->id, [
            'quantity' => '2',
            'unit' => 'cups',
            'preparation' => ''
        ]);

        $recipe->ingredients()->attach($sugar->id, [
            'quantity' => '1',
            'unit' => 'cup',
            'preparation' => ''
        ]);

        // Create steps
        $recipe->steps()->create([
            'step_number' => 1,
            'description' => 'Mix dry ingredients',
            'duration_minutes' => null
        ]);

        $recipe->steps()->create([
            'step_number' => 2,
            'description' => 'Bake at 350°F',
            'duration_minutes' => 30
        ]);

        // Refresh the recipe with relationships
        $recipe = Recipe::with(['ingredients', 'steps'])->find($recipe->id);
        $this->assertNotNull($recipe, 'Recipe not found after creation');

        // Force loading of relationships
        $ingredients = $recipe->ingredients()->get();
        $steps = $recipe->steps()->get();

        // Test ingredients relationship
        $this->assertNotEmpty($ingredients, 'Ingredients relationship is empty');
        $this->assertCount(2, $ingredients);
        $this->assertEquals('flour', $ingredients[0]->name);
        $this->assertEquals('sugar', $ingredients[1]->name);
        $this->assertEquals('2', $ingredients[0]->pivot->quantity);
        $this->assertEquals('cups', $ingredients[0]->pivot->unit);

        // Test steps relationship
        $this->assertNotEmpty($steps, 'Steps relationship is empty');
        $this->assertCount(2, $steps);
        $this->assertEquals(1, $steps[0]->step_number);
        $this->assertEquals(2, $steps[1]->step_number);
        $this->assertEquals('Mix dry ingredients', $steps[0]->description);
        $this->assertEquals(30, $steps[1]->duration_minutes);

        // Test ordering of steps
        $recipe->steps()->create([
            'step_number' => 1.5, // Insert between steps 1 and 2
            'description' => 'Add wet ingredients',
            'duration_minutes' => 5
        ]);

        $steps = $recipe->steps()->orderBy('step_number')->get();
        $this->assertNotEmpty($steps, 'Steps relationship is empty after adding new step');
        $stepDescriptions = $steps->pluck('description')->toArray();

        $this->assertEquals([
            'Mix dry ingredients',
            'Add wet ingredients',
            'Bake at 350°F'
        ], $stepDescriptions);
    }

    /**
     * Test the withRelations factory method.
     */
    public function test_factory_with_relations(): void
    {
        // Create a recipe with ingredients and steps using the factory
        $recipe = Recipe::factory()
            ->withRelations(5, 3) // 5 ingredients, 3 steps
            ->create();

        // Refresh the recipe with relationships
        $recipe = Recipe::with(['ingredients', 'steps'])->find($recipe->id);
        $this->assertNotNull($recipe, 'Recipe not found after creation');

        // Force loading of relationships
        $ingredients = $recipe->ingredients()->get();
        $steps = $recipe->steps()->orderBy('step_number')->get();

        // Test that relationships were created
        $this->assertNotEmpty($ingredients, 'Ingredients relationship is empty');
        $this->assertCount(5, $ingredients);

        $this->assertNotEmpty($steps, 'Steps relationship is empty');
        $this->assertCount(3, $steps);

        // Test that steps are in order
        $this->assertEquals(1, $steps[0]->step_number);
        $this->assertEquals(2, $steps[1]->step_number);
        $this->assertEquals(3, $steps[2]->step_number);
    }
}
