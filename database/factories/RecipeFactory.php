<?php

namespace Database\Factories;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Recipe::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        
        return [
            'name' => ucfirst($name),
            'description' => $this->faker->paragraph(),
            'author_email' => $this->faker->email(),
            'slug' => Str::slug($name),
        ];
    }
    
    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Recipe $recipe) {
            // By default, don't create relationships
            // Use the withIngredients() and withSteps() methods to add them
        });
    }
    
    /**
     * Add ingredients to the recipe.
     *
     * @param int $count Number of ingredients to create
     * @return $this
     */
    public function withIngredients(int $count = 3)
    {
        return $this->afterCreating(function (Recipe $recipe) use ($count) {
            $ingredients = \App\Models\Ingredient::factory()->count($count)->create();
            
            foreach ($ingredients as $ingredient) {
                $recipe->ingredients()->attach($ingredient->id, [
                    'quantity' => $this->faker->optional(0.8, '1')->randomElement(['1', '2', '1/2', '1/4', '3']),
                    'unit' => $this->faker->optional(0.8, '')->randomElement(['cup', 'tablespoon', 'teaspoon', 'ounce', 'pound', '']),
                    'preparation' => $this->faker->optional(0.5, '')->randomElement(['chopped', 'diced', 'minced', 'sliced', 'grated']),
                ]);
            }
        });
    }
    
    /**
     * Add steps to the recipe.
     *
     * @param int $count Number of steps to create
     * @return $this
     */
    public function withSteps(int $count = 4)
    {
        return $this->afterCreating(function (Recipe $recipe) use ($count) {
            for ($i = 1; $i <= $count; $i++) {
                $recipe->steps()->create([
                    'step_number' => $i,
                    'description' => $this->faker->sentence(10),
                    'duration_minutes' => $this->faker->optional(0.6, null)->numberBetween(1, 60),
                ]);
            }
        });
    }
    
    /**
     * Add both ingredients and steps to the recipe.
     *
     * @param int $ingredientCount Number of ingredients to create
     * @param int $stepCount Number of steps to create
     * @return $this
     */
    public function withRelations(int $ingredientCount = 3, int $stepCount = 4)
    {
        return $this->withIngredients($ingredientCount)->withSteps($stepCount);
    }
}