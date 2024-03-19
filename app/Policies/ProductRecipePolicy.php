<?php

namespace App\Policies;

use App\Models\ProductRecipe;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductRecipePolicy
{
    public function view(User $user, ProductRecipe $productRecipe): Response
    {
        return $user->id === $productRecipe->recipe?->user?->id ? Response::allow() : Response::denyWithStatus(404);
    }

    public function create(User $user): Response
    {
        $productId = request()->product_id;
        $recipeId = request()->recipe_id;

        if (! is_string($productId)) {
            return Response::denyWithStatus(422, 'product_id must be a string.');
        } elseif (! is_string($recipeId)) {
            return Response::denyWithStatus(422, 'recipe_id must be a string.');
        }

        $userRelatedProduct = $user->products()->find($productId);
        $userRelatedRecipe = $user->recipes()->find($recipeId);

        if (! $userRelatedProduct) {
            return Response::denyWithStatus(404, 'Product not found.');
        } elseif (! $userRelatedRecipe) {
            return Response::denyWithStatus(404, 'Recipe not found.');
        }

        return Response::allow();
    }

    public function delete(User $user, ProductRecipe $productRecipe): Response
    {
        return $user->id === $productRecipe->recipe?->user?->id ? Response::allow() : Response::denyWithStatus(404);
    }
}
