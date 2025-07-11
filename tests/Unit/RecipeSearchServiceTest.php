<?php

namespace Tests\Unit;

use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\RecipeStep;
use App\Services\RecipeSearchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeSearchServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var RecipeSearchService
     */
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new RecipeSearchService();
    }

    public function test_it_can_search_recipes_without_filters()
    {
        // Create test recipes
        Recipe::factory()->count(3)->create()->each(function ($recipe) {
            // Add ingredients
            $ingredients = Ingredient::factory()->count(3)->create();
            $recipe->ingredients()->attach($ingredients, [
                'quantity' => '1',
                'unit' => 'cup',
                'preparation' => 'chopped',
            ]);

            // Add steps with unique step numbers
            for ($i = 1; $i <= 3; $i++) {
                $recipe->steps()->save(
                    RecipeStep::factory()->make([
                        'recipe_id' => $recipe->id,
                        'step_number' => $i
                    ])
                );
            }
        });

        // Search without filters
        $result = $this->service->search([]);

        // Assert we get all recipes
        $this->assertEquals(3, $result->total());
        $this->assertCount(3, $result->items());
    }

    public function test_it_can_search_recipes_by_author_email()
    {
        // Create recipes with different author emails
        $recipe1 = Recipe::factory()->create(['author_email' => 'author1@example.com']);
        $recipe2 = Recipe::factory()->create(['author_email' => 'author2@example.com']);
        $recipe3 = Recipe::factory()->create(['author_email' => 'author1@example.com']);

        // Search by author email
        $result = $this->service->search(['author_email' => 'author1@example.com']);

        // Assert we get only recipes by author1
        $this->assertEquals(2, $result->total());
        $this->assertCount(2, $result->items());

        // Verify the correct recipes were returned
        $recipeIds = collect($result->items())->pluck('id')->toArray();
        $this->assertContains($recipe1->id, $recipeIds);
        $this->assertContains($recipe3->id, $recipeIds);
        $this->assertNotContains($recipe2->id, $recipeIds);
    }

    public function test_it_can_search_recipes_by_keyword()
    {
        // Create recipes with different names and descriptions
        $recipe1 = Recipe::factory()->create([
            'name' => 'Chocolate Cake',
            'description' => 'A delicious dessert'
        ]);
        $recipe2 = Recipe::factory()->create([
            'name' => 'Vanilla Pudding',
            'description' => 'A simple dessert'
        ]);
        $recipe3 = Recipe::factory()->create([
            'name' => 'Beef Stew',
            'description' => 'A hearty meal with chocolate sauce'
        ]);

        // Search by keyword
        $result = $this->service->search(['keyword' => 'chocolate']);

        // Assert we get only recipes containing "chocolate"
        $this->assertEquals(2, $result->total());

        // Verify the correct recipes were returned
        $recipeIds = collect($result->items())->pluck('id')->toArray();
        $this->assertContains($recipe1->id, $recipeIds);
        $this->assertContains($recipe3->id, $recipeIds);
        $this->assertNotContains($recipe2->id, $recipeIds);
    }

    public function test_it_can_search_recipes_by_ingredient()
    {
        // Create recipes
        $recipe1 = Recipe::factory()->create();
        $recipe2 = Recipe::factory()->create();
        $recipe3 = Recipe::factory()->create();

        // Create ingredients
        $ingredient1 = Ingredient::factory()->create(['name' => 'Sugar']);
        $ingredient2 = Ingredient::factory()->create(['name' => 'Salt']);
        $ingredient3 = Ingredient::factory()->create(['name' => 'Flour']);

        // Attach ingredients to recipes
        $recipe1->ingredients()->attach([
            $ingredient1->id => ['quantity' => '1', 'unit' => 'cup', 'preparation' => ''],
            $ingredient3->id => ['quantity' => '2', 'unit' => 'cups', 'preparation' => ''],
        ]);

        $recipe2->ingredients()->attach([
            $ingredient2->id => ['quantity' => '1', 'unit' => 'tsp', 'preparation' => ''],
        ]);

        $recipe3->ingredients()->attach([
            $ingredient1->id => ['quantity' => '2', 'unit' => 'tbsp', 'preparation' => ''],
            $ingredient2->id => ['quantity' => '1', 'unit' => 'pinch', 'preparation' => ''],
        ]);

        // Search by ingredient
        $result = $this->service->search(['ingredient' => 'sugar']);

        // Assert we get only recipes containing "sugar"
        $this->assertEquals(2, $result->total());

        // Verify the correct recipes were returned
        $recipeIds = collect($result->items())->pluck('id')->toArray();
        $this->assertContains($recipe1->id, $recipeIds);
        $this->assertContains($recipe3->id, $recipeIds);
        $this->assertNotContains($recipe2->id, $recipeIds);
    }

    public function test_it_can_combine_multiple_search_filters()
    {
        // Create recipes with different attributes
        $recipe1 = Recipe::factory()->create([
            'name' => 'Chocolate Cake',
            'description' => 'A delicious dessert',
            'author_email' => 'baker@example.com'
        ]);

        $recipe2 = Recipe::factory()->create([
            'name' => 'Chocolate Cookies',
            'description' => 'Crunchy cookies',
            'author_email' => 'chef@example.com'
        ]);

        $recipe3 = Recipe::factory()->create([
            'name' => 'Vanilla Cake',
            'description' => 'A simple cake',
            'author_email' => 'baker@example.com'
        ]);

        // Create and attach ingredients
        $sugar = Ingredient::factory()->create(['name' => 'Sugar']);

        $recipe1->ingredients()->attach([
            $sugar->id => ['quantity' => '1', 'unit' => 'cup', 'preparation' => ''],
        ]);

        $recipe2->ingredients()->attach([
            $sugar->id => ['quantity' => '1/2', 'unit' => 'cup', 'preparation' => ''],
        ]);

        $recipe3->ingredients()->attach([
            $sugar->id => ['quantity' => '3/4', 'unit' => 'cup', 'preparation' => ''],
        ]);

        // Search with multiple filters
        $result = $this->service->search([
            'keyword' => 'chocolate',
            'author_email' => 'baker@example.com',
            'ingredient' => 'sugar'
        ]);

        // Assert we get only the recipe that matches all criteria
        $this->assertEquals(1, $result->total());
        $this->assertEquals($recipe1->id, $result->items()[0]->id);
    }

    public function test_it_can_find_recipe_by_slug()
    {
        // Create a recipe with a known slug
        $recipe = Recipe::factory()->create(['slug' => 'test-recipe']);

        // Add ingredients and steps
        $ingredients = Ingredient::factory()->count(2)->create();
        $recipe->ingredients()->attach($ingredients, [
            'quantity' => '1',
            'unit' => 'cup',
            'preparation' => 'chopped',
        ]);

        // Add steps with unique step numbers
        for ($i = 1; $i <= 2; $i++) {
            $recipe->steps()->save(
                RecipeStep::factory()->make([
                    'recipe_id' => $recipe->id,
                    'step_number' => $i
                ])
            );
        }

        // Find the recipe by slug
        $foundRecipe = $this->service->findBySlug('test-recipe');

        // Assert we found the correct recipe
        $this->assertEquals($recipe->id, $foundRecipe->id);
        $this->assertEquals('test-recipe', $foundRecipe->slug);

        // Assert relationships are loaded
        $this->assertTrue($foundRecipe->relationLoaded('ingredients'));
        $this->assertTrue($foundRecipe->relationLoaded('steps'));
    }
}
