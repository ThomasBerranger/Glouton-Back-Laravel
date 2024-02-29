<?php

namespace App\Http\Controllers;

use App\Enums\Product\Filter;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\ExpirationDate;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        $user = Auth::user();

        $request = $request->toArray();

        if (!array_key_exists('filter', $request)) {
            return ProductResource::collection($user?->products()->groupedByMinExpirationDate()->orderedBy('closest_expiration_date')->get());
        } else if (array_key_exists('category', $request['filter'])) {
            return match ($request['filter']['category']) {
                Filter::WEEK->value => ProductResource::collection($user?->products()->notFinished()->week()->get()),
                Filter::MONTH->value => ProductResource::collection($user?->products()->notFinished()->month()->get()),
                Filter::YEARS->value => ProductResource::collection($user?->products()->notFinished()->years()->get()),
                Filter::FINISHED->value => ProductResource::collection($user?->products()->finished()->orderedBy('finished_at', false)->get()),
                Filter::TO_PURCHASE->value => ProductResource::collection($user?->products()->toPurchase()->orderedBy('added_to_purchase_list_at')->get()),
                default => response()->json(['error' => 'Category value unknown'], 400)
            };
        }

        return response()->json(['error' => 'Filter unknown'], 400);
    }

    public function store(StoreProductRequest $request): ProductResource
    {
        $product = new Product($request->validated());

        DB::transaction(function () use ($product, $request) {
            $product->save();

            $expirationDates = $request->safe()->only('expiration_dates')['expiration_dates'];

            foreach ($expirationDates as $expirationDate) {
                ExpirationDate::create(['product_id' => $product->id, 'date' => $expirationDate['date']]);
            }
        });

        return ProductResource::make($product->load('expirationDates'));
    }

    public function show(Request $request, Product $product): ProductResource
    {
        return ProductResource::make($product->load('expirationDates'));
    }

    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        $product->update($request->validated());

        return ProductResource::make($product);
    }

    public function destroy(Product $product): Response
    {
        $product->delete();

        return response()->noContent();
    }
}
