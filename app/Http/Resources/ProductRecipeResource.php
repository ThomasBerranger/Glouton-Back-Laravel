<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductRecipeResource extends JsonResource
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
            'product' => ProductResource::make($this->whenLoaded('product')),
            'recipe' => RecipeResource::make($this->whenLoaded('recipe')),
            'quantity' => $this->quantity,
            'quantity_unity' => $this->quantity_unity,
        ];
    }
}
