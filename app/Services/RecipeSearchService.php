<?php

namespace App\Services;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class RecipeSearchService
{
    /**
     * The default number of recipes per page.
     *
     * @var int
     */
    protected $perPage = 10;

    /**
     * Search for recipes based on the provided filters.
     *
     * @param Request|array $filters
     * @param int|null $perPage
     * @return LengthAwarePaginator
     */
    public function search($filters, ?int $perPage = null): LengthAwarePaginator
    {
        $query = Recipe::query();

        // Convert Request to array if needed
        $searchParams = $filters instanceof Request ? $filters->all() : $filters;

        // Apply filters
        $this->applyFilters($query, $searchParams);

        // Load relationships
        $query->with(['ingredients', 'steps']);

        // Get paginated results
        return $query->paginate($perPage ?? $this->perPage)->withQueryString();
    }

    /**
     * Find a recipe by its slug.
     *
     * @param string $slug
     * @return Recipe
     */
    public function findBySlug(string $slug): Recipe
    {
        return Recipe::where('slug', $slug)
            ->with(['ingredients', 'steps'])
            ->firstOrFail();
    }

    /**
     * Apply search filters to the query.
     *
     * @param Builder $query
     * @param array $filters
     * @return void
     */
    protected function applyFilters(Builder $query, array $filters): void
    {
        if (isset($filters['author_email'])) {
            $query->byAuthorEmail($filters['author_email']);
        }

        if (isset($filters['keyword'])) {
            $query->byKeyword($filters['keyword']);
        }

        if (isset($filters['ingredient'])) {
            $query->byIngredient($filters['ingredient']);
        }
    }
}
