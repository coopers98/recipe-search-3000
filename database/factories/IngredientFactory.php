<?php

namespace Database\Factories;

use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ingredient>
 */
class IngredientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ingredient::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'flour', 'sugar', 'salt', 'pepper', 'olive oil', 'butter',
                'eggs', 'milk', 'cheese', 'chicken', 'beef', 'pork',
                'onion', 'garlic', 'tomato', 'potato', 'carrot', 'celery',
                'rice', 'pasta', 'bread', 'lemon', 'lime', 'orange',
                'apple', 'banana', 'strawberry', 'blueberry', 'chocolate',
                'vanilla', 'cinnamon', 'oregano', 'basil', 'thyme',
                'cumin', 'paprika', 'cayenne pepper', 'ginger', 'nutmeg',
                'honey', 'maple syrup', 'soy sauce', 'vinegar', 'wine',
                'cream', 'yogurt', 'sour cream', 'cream cheese', 'bacon',
                'mushroom', 'spinach', 'kale', 'broccoli', 'cauliflower',
                'bell pepper', 'jalapeño', 'avocado', 'cucumber', 'zucchini',
                'eggplant', 'corn', 'peas', 'beans', 'lentils',
                'almonds', 'walnuts', 'pecans', 'peanuts', 'cashews',
                'coconut', 'raisins', 'cranberries', 'dates', 'oats',
                'quinoa', 'brown rice', 'whole wheat flour', 'baking powder',
                'baking soda', 'yeast', 'mustard', 'ketchup', 'mayonnaise',
                'salsa', 'hot sauce', 'barbecue sauce', 'pesto', 'hummus'
            ]),
        ];
    }
}