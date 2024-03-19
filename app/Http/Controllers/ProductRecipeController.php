<?php

namespace App\Http\Controllers;

use App\Http\Requests\Recipe\StoreRecipeRequest;
use App\Http\Requests\StoreProductRecipeRequest;
use App\Http\Resources\ProductRecipeResource;
use App\Http\Resources\RecipeResource;
use App\Models\ProductRecipe;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProductRecipeController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ProductRecipe::class, 'productRecipe');
    }

    public function show(Request $request, ProductRecipe $productRecipe): ProductRecipeResource
    {
        return ProductRecipeResource::make($productRecipe);
    }

    public function store(StoreProductRecipeRequest $request): ProductRecipeResource
    {
        $productRecipe = ProductRecipe::create($request->validated());

        return ProductRecipeResource::make($productRecipe);
    }

    public function destroy(ProductRecipe $productRecipe): Response
    {
        $productRecipe->delete();

        return response()->noContent();
    }
}
