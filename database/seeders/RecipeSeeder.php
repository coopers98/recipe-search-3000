<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeStep;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Start a transaction
        DB::beginTransaction();

        try {
            $recipes = [
                [
                    'name' => 'Classic Spaghetti Carbonara',
                    'description' => 'A traditional Italian pasta dish with eggs, cheese, pancetta, and black pepper.',
                    'ingredients' => [
                        ['name' => 'spaghetti', 'quantity' => '400', 'unit' => 'g'],
                        ['name' => 'pancetta or guanciale', 'quantity' => '200', 'unit' => 'g', 'preparation' => 'diced'],
                        ['name' => 'eggs', 'quantity' => '4', 'unit' => 'large'],
                        ['name' => 'Pecorino Romano cheese', 'quantity' => '100', 'unit' => 'g', 'preparation' => 'grated'],
                        ['name' => 'Parmesan cheese', 'quantity' => '50', 'unit' => 'g', 'preparation' => 'grated'],
                        ['name' => 'black pepper', 'preparation' => 'freshly ground'],
                        ['name' => 'salt', 'preparation' => 'to taste']
                    ],
                    'steps' => [
                        ['step_number' => 1, 'description' => 'Bring a large pot of salted water to boil and cook spaghetti according to package instructions.', 'duration_minutes' => 10],
                        ['step_number' => 2, 'description' => 'While pasta is cooking, heat a large skillet over medium heat and cook the pancetta until crispy.', 'duration_minutes' => 5],
                        ['step_number' => 3, 'description' => 'In a bowl, whisk together eggs, grated cheeses, and black pepper.'],
                        ['step_number' => 4, 'description' => 'Drain the pasta, reserving some pasta water.'],
                        ['step_number' => 5, 'description' => 'Working quickly, add hot pasta to the skillet with pancetta, remove from heat.'],
                        ['step_number' => 6, 'description' => 'Pour the egg and cheese mixture over the pasta, stirring constantly to create a creamy sauce.'],
                        ['step_number' => 7, 'description' => 'If needed, add a splash of reserved pasta water to loosen the sauce.'],
                        ['step_number' => 8, 'description' => 'Serve immediately with extra grated cheese and black pepper.']
                    ],
                    'author_email' => 'chef@example.com',
                ],
                [
                    'name' => 'Homemade Margherita Pizza',
                    'description' => 'A classic Italian pizza with fresh mozzarella, tomatoes, and basil.',
                    'ingredients' => [
                        ['name' => 'pizza dough ball', 'quantity' => '1', 'unit' => '', 'preparation' => 'about 250g'],
                        ['name' => 'tomato sauce', 'quantity' => '3', 'unit' => 'tablespoons'],
                        ['name' => 'fresh mozzarella cheese', 'quantity' => '125', 'unit' => 'g', 'preparation' => 'sliced'],
                        ['name' => 'fresh basil leaves', 'quantity' => '5-6'],
                        ['name' => 'extra virgin olive oil', 'quantity' => '2', 'unit' => 'tablespoons'],
                        ['name' => 'salt', 'preparation' => 'to taste'],
                        ['name' => 'pepper', 'preparation' => 'to taste']
                    ],
                    'steps' => [
                        ['step_number' => 1, 'description' => 'Preheat your oven to the highest temperature (usually 500°F/260°C) with a pizza stone if available.', 'duration_minutes' => 10],
                        ['step_number' => 2, 'description' => 'Stretch the pizza dough into a 12-inch circle on a floured surface.', 'duration_minutes' => 5],
                        ['step_number' => 3, 'description' => 'Spread tomato sauce evenly over the dough, leaving a small border for the crust.'],
                        ['step_number' => 4, 'description' => 'Arrange mozzarella slices over the sauce.'],
                        ['step_number' => 5, 'description' => 'Bake for 8-10 minutes until the crust is golden and cheese is bubbly.', 'duration_minutes' => 10],
                        ['step_number' => 6, 'description' => 'Remove from oven and immediately top with fresh basil leaves.'],
                        ['step_number' => 7, 'description' => 'Drizzle with olive oil, season with salt and pepper, and serve hot.']
                    ],
                    'author_email' => 'pizza@example.com',
                ],
                [
                    'name' => 'Creamy Potato Soup',
                    'description' => 'A hearty and comforting soup perfect for cold days.',
                    'ingredients' => [
                        ['name' => 'potatoes', 'quantity' => '6', 'unit' => 'large', 'preparation' => 'peeled and diced'],
                        ['name' => 'onion', 'quantity' => '1', 'unit' => 'large', 'preparation' => 'chopped'],
                        ['name' => 'garlic', 'quantity' => '3', 'unit' => 'cloves', 'preparation' => 'minced'],
                        ['name' => 'chicken or vegetable broth', 'quantity' => '4', 'unit' => 'cups'],
                        ['name' => 'heavy cream', 'quantity' => '1', 'unit' => 'cup'],
                        ['name' => 'butter', 'quantity' => '2', 'unit' => 'tablespoons'],
                        ['name' => 'dried thyme', 'quantity' => '1', 'unit' => 'teaspoon'],
                        ['name' => 'salt', 'preparation' => 'to taste'],
                        ['name' => 'pepper', 'preparation' => 'to taste'],
                        ['name' => 'chives', 'preparation' => 'chopped, for garnish'],
                        ['name' => 'bacon bits', 'preparation' => 'crispy']
                    ],
                    'steps' => [
                        ['step_number' => 1, 'description' => 'In a large pot, melt butter over medium heat and sauté onions until translucent.', 'duration_minutes' => 5],
                        ['step_number' => 2, 'description' => 'Add garlic and cook for another minute until fragrant.', 'duration_minutes' => 1],
                        ['step_number' => 3, 'description' => 'Add diced potatoes, broth, thyme, salt, and pepper.'],
                        ['step_number' => 4, 'description' => 'Bring to a boil, then reduce heat and simmer for 15-20 minutes until potatoes are tender.', 'duration_minutes' => 20],
                        ['step_number' => 5, 'description' => 'Using an immersion blender, blend soup until smooth (or leave some chunks for texture).', 'duration_minutes' => 2],
                        ['step_number' => 6, 'description' => 'Stir in heavy cream and heat through without boiling.', 'duration_minutes' => 2],
                        ['step_number' => 7, 'description' => 'Serve hot, garnished with chives and bacon bits if desired.']
                    ],
                    'author_email' => 'soup@example.com',
                ],
                [
                    'name' => 'Fresh Garden Salad',
                    'description' => 'A light and refreshing salad with seasonal vegetables.',
                    'ingredients' => [
                        ['name' => 'mixed salad greens', 'quantity' => '4', 'unit' => 'cups'],
                        ['name' => 'cherry tomatoes', 'quantity' => '1', 'unit' => 'cup', 'preparation' => 'halved'],
                        ['name' => 'cucumber', 'quantity' => '1', 'unit' => 'medium', 'preparation' => 'sliced'],
                        ['name' => 'red onion', 'quantity' => '1/4', 'unit' => '', 'preparation' => 'thinly sliced'],
                        ['name' => 'bell pepper', 'quantity' => '1', 'unit' => '', 'preparation' => 'diced'],
                        ['name' => 'avocado', 'quantity' => '1', 'unit' => '', 'preparation' => 'diced'],
                        ['name' => 'olive oil', 'quantity' => '3', 'unit' => 'tablespoons'],
                        ['name' => 'lemon juice', 'quantity' => '2', 'unit' => 'tablespoons'],
                        ['name' => 'Dijon mustard', 'quantity' => '1', 'unit' => 'teaspoon'],
                        ['name' => 'honey', 'quantity' => '1', 'unit' => 'teaspoon'],
                        ['name' => 'salt', 'preparation' => 'to taste'],
                        ['name' => 'black pepper', 'preparation' => 'to taste']
                    ],
                    'steps' => [
                        ['step_number' => 1, 'description' => 'In a large salad bowl, combine all the vegetables.'],
                        ['step_number' => 2, 'description' => 'In a small bowl, whisk together olive oil, lemon juice, mustard, honey, salt, and pepper to make the dressing.'],
                        ['step_number' => 3, 'description' => 'Pour the dressing over the salad just before serving.'],
                        ['step_number' => 4, 'description' => 'Toss gently to coat all ingredients with the dressing.'],
                        ['step_number' => 5, 'description' => 'Serve immediately as a side dish or add protein for a main course.']
                    ],
                    'author_email' => 'salad@example.com',
                ],
            ];

            foreach ($recipes as $recipeData) {
                // Extract ingredients and steps data
                $ingredientsData = $recipeData['ingredients'];
                $stepsData = $recipeData['steps'];

                // Remove them from the recipe data for initial creation
                unset($recipeData['ingredients']);
                unset($recipeData['steps']);

                // Create the recipe
                $recipe = Recipe::create($recipeData);

                // Process ingredients
                foreach ($ingredientsData as $ingredientData) {
                    // Create or find the ingredient
                    $ingredient = Ingredient::firstOrCreate([
                        'name' => trim($ingredientData['name'])
                    ]);

                    // Prepare pivot data
                    $pivotData = [
                        'quantity' => $ingredientData['quantity'] ?? null,
                        'unit' => $ingredientData['unit'] ?? null,
                        'preparation' => $ingredientData['preparation'] ?? null,
                    ];

                    // Attach ingredient to recipe with pivot data
                    $recipe->ingredients()->attach($ingredient->id, $pivotData);
                }

                // Process steps
                foreach ($stepsData as $stepData) {
                    // Create step directly related to recipe
                    $recipe->steps()->create([
                        'step_number' => $stepData['step_number'],
                        'description' => $stepData['description'],
                        'duration_minutes' => $stepData['duration_minutes'] ?? null,
                    ]);
                }
            }

            // Create additional random recipes to reach 25 total
            $additionalRecipesCount = 25 - count($recipes);
            
            if ($additionalRecipesCount > 0) {
                Recipe::factory()
                    ->count($additionalRecipesCount)
                    ->create()
                    ->each(function ($recipe) {
                        // Add 3-8 random ingredients to each recipe
                        $ingredientCount = rand(3, 8);
                        
                        // Generate ingredient names first to ensure uniqueness
                        $ingredientNames = [];
                        for ($i = 0; $i < $ingredientCount; $i++) {
                            // Generate a random ingredient name
                            $name = fake()->unique()->word();
                            $ingredientNames[] = $name;
                        }
                        
                        // Create or find ingredients
                        $ingredients = [];
                        foreach ($ingredientNames as $name) {
                            // Use firstOrCreate to avoid duplicates
                            $ingredient = Ingredient::firstOrCreate(['name' => $name]);
                            $ingredients[] = $ingredient;
                        }
                        
                        // Attach ingredients to recipe
                        foreach ($ingredients as $ingredient) {
                            $recipe->ingredients()->attach($ingredient->id, [
                                'quantity' => rand(1, 10),
                                'unit' => ['g', 'kg', 'ml', 'l', 'cup', 'tbsp', 'tsp', 'piece'][rand(0, 7)],
                                'preparation' => ['chopped', 'diced', 'sliced', 'minced', 'grated', ''][rand(0, 5)],
                            ]);
                        }
                        
                        // Add 3-10 steps to each recipe
                        $stepCount = rand(3, 10);
                        for ($i = 1; $i <= $stepCount; $i++) {
                            $recipe->steps()->create([
                                'step_number' => $i,
                                'description' => fake()->sentence(rand(10, 20)),
                                'duration_minutes' => rand(0, 1) ? rand(1, 60) : null,
                            ]);
                        }
                    });
            }
            
            // Commit the transaction
            DB::commit();

        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();

            // Re-throw the exception
            throw $e;
        }
    }
}
