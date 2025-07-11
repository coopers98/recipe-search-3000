<?php

namespace Tests\Unit;

use App\Http\Controllers\RecipeController;
use App\Http\Requests\RecipeSearchRequest;
use App\Http\Requests\RecipeShowRequest;
use App\Http\Resources\RecipeCollection;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use App\Services\RecipeSearchService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class RecipeControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_method_uses_recipe_search_service()
    {
        // Create a mock of the RecipeSearchService
        $mockService = Mockery::mock(RecipeSearchService::class);

        // Create a mock paginator
        $mockPaginator = new LengthAwarePaginator(
            [new Recipe()], // items
            1, // total
            10, // per page
            1 // current page
        );

        // Set up expectations
        $mockService->shouldReceive('search')
            ->once()
            ->with(Mockery::type(RecipeSearchRequest::class))
            ->andReturn($mockPaginator);

        // Create controller with mock service
        $controller = new RecipeController($mockService);

        // Create a mock request
        $request = Mockery::mock(RecipeSearchRequest::class);
        $request->shouldReceive('validated')->andReturn([]);

        // Call the index method
        $response = $controller->index($request);

        //  Assert the response is a collection of RecipeResource
        $this->assertInstanceOf(\Illuminate\Http\Resources\Json\AnonymousResourceCollection::class, $response);
        $this->assertEquals(\App\Http\Resources\RecipeResource::class, $response->collects);
    }

    public function test_show_method_uses_recipe_search_service()
    {
        // Create a mock recipe
        $mockRecipe = new Recipe();
        $mockRecipe->id = 1;
        $mockRecipe->name = 'Test Recipe';
        $mockRecipe->slug = 'test-recipe';

        // Create a mock of the RecipeSearchService
        $mockService = Mockery::mock(RecipeSearchService::class);

        // Set up expectations
        $mockService->shouldReceive('findBySlug')
            ->once()
            ->with('test-recipe')
            ->andReturn($mockRecipe);

        // Create controller with mock service
        $controller = new RecipeController($mockService);

        // Create a mock request
        $request = Mockery::mock(RecipeShowRequest::class);
        $request->slug = 'test-recipe';
        $request->shouldReceive('validated')->andReturn(['slug' => 'test-recipe']);

        // Call the show method
        $response = $controller->show($request);

        // Assert the response is a RecipeResource
        $this->assertInstanceOf(RecipeResource::class, $response);
    }
}
