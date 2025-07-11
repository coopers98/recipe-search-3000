<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'author_email',
        'slug',
    ];

    /**
     * Get the steps for the recipe.
     */
    public function steps(): HasMany
    {
        return $this->hasMany(RecipeStep::class)->orderBy('step_number');
    }

    /**
     * Get the ingredients for the recipe.
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredient')
            ->withPivot(['quantity', 'unit', 'preparation'])
            ->withTimestamps();
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($recipe) {
            // Generate a unique slug based on the name if not provided
            if (empty($recipe->slug)) {
                $recipe->slug = Str::slug($recipe->name);

                // Ensure the slug is unique
                $count = 1;
                $originalSlug = $recipe->slug;

                while (static::where('slug', $recipe->slug)->exists()) {
                    $recipe->slug = $originalSlug . '-' . $count++;
                }
            }
        });
    }

    /**
     * Scope a query to search by author email.
     */
    public function scopeByAuthorEmail($query, $email)
    {
        if ($email) {
            return $query->where('author_email', $email);
        }

        return $query;
    }

    /**
     * Scope a query to search by keyword.
     */
    public function scopeByKeyword($query, $keyword)
    {
        if ($keyword) {
            return $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhereHas('steps', function ($stepQuery) use ($keyword) {
                      $stepQuery->where('description', 'like', "%{$keyword}%");
                  })
                  ->orWhereHas('ingredients', function ($ingredientQuery) use ($keyword) {
                      $ingredientQuery->where('name', 'like', "%{$keyword}%")
                          ->orWhere('recipe_ingredient.preparation', 'like', "%{$keyword}%");
                  });
            });
        }

        return $query;
    }

    /**
     * Scope a query to search by ingredient.
     */
    public function scopeByIngredient($query, $ingredient)
    {
        if ($ingredient) {
            return $query->where(function ($q) use ($ingredient) {
                $q->whereHas('ingredients', function ($ingredientQuery) use ($ingredient) {
                    $ingredientQuery->where('name', 'like', "%{$ingredient}%");
                });
            });
        }

        return $query;
    }
}
