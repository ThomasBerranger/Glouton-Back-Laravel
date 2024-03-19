<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    /**
     * @return array<string, string>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'available' => $this->isAvailable(), // @phpstan-ignore-line
            'user' => UserResource::make($this->user),
            'productsRecipes' => ProductRecipeResource::collection($this->productsRecipes),
        ];
    }
}
