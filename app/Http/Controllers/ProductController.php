<?php

namespace App\Http\Controllers;

use App\Enums\Product\Filter;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Spatie\FlareClient\Http\Exceptions\NotFound;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = Auth::user();


        if ($request->has('filter')) {
            if ($request->get('filter')['expiration_date'] === Filter::WEEK->value) {
                return ProductResource::collection($user->products()->get()->where('id', 1));
            }

            dd($request->get('filter'));
        }


        return ProductResource::collection($user->products()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): ProductResource
    {
        $product = Product::create($request->validated());

        return ProductResource::make($product);
    }

    /**
     * Display the specified resource.
     * @throws \Throwable
     */
    public function show(Request $request, Product $product): ProductResource
    {
        throw_if($product->user()->first()->id !== Auth::user()->id, NotFoundHttpException::class);

        return ProductResource::make($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        $product->update($request->validated());

        return ProductResource::make($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): Response
    {
        $product->delete();

        return response()->noContent();
    }
}
