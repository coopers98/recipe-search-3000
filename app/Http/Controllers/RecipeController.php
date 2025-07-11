<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecipeSearchRequest;
use App\Http\Requests\RecipeShowRequest;
use App\Http\Resources\RecipeResource;
use App\Http\Resources\RecipeCollection;
use App\Services\RecipeSearchService;

class RecipeController extends Controller
{
    /**
     * The recipe search service instance.
     *
     * @var RecipeSearchService
     */
    protected $recipeSearchService;

    /**
     * Create a new controller instance.
     *
     * @param RecipeSearchService $recipeSearchService
     * @return void
     */
    public function __construct(RecipeSearchService $recipeSearchService)
    {
        $this->recipeSearchService = $recipeSearchService;
    }

    /**
     * Display a listing of recipes with optional search filters.
     *
     * @param RecipeSearchRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(RecipeSearchRequest $request)
    {
        $recipes = $this->recipeSearchService->search($request);

        return RecipeResource::collection($recipes);
    }

    /**
     * Display the specified recipe.
     *
     * @param RecipeShowRequest $request
     * @return RecipeResource
     */
    public function show(RecipeShowRequest $request)
    {
        $recipe = $this->recipeSearchService->findBySlug($request->slug);

        return new RecipeResource($recipe);
    }
}
