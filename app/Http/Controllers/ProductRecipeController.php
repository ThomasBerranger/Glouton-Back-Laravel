<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRecipeRequest;
use App\Http\Resources\ProductRecipeResource;
use App\Models\ProductRecipe;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductRecipeController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ProductRecipe::class, 'productRecipe');
    }

    public function store(StoreProductRecipeRequest $request): ProductRecipeResource
    {
        $productRecipe = ProductRecipe::create($request->validated());

        return ProductRecipeResource::make($productRecipe);
    }

    public function show(Request $request, ProductRecipe $productRecipe): ProductRecipeResource
    {
        return ProductRecipeResource::make($productRecipe);
    }

    public function destroy(ProductRecipe $productRecipe): Response
    {
        $productRecipe->delete();

        return response()->noContent();
    }
}
