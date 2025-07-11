<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'ingredients' => $this->whenLoaded('ingredients', function () {
                return IngredientResource::collection($this->ingredients);
            }, []),
            'steps' => $this->whenLoaded('steps', function () {
                return RecipeStepResource::collection($this->steps);
            }, []),
            'author_email' => $this->author_email,
            'slug' => $this->slug,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}