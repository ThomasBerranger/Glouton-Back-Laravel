<?php

namespace App\Http\Controllers;

use App\Enums\Product\Filter;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ExpirationDatesResource;
use App\Http\Resources\ProductResource;
use App\Models\ExpirationDate;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\FlareClient\Http\Exceptions\NotFound;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = Auth::user();

//        if ($request->has('filter')) {
//            return match ($request->get('filter')['expiration_date']) {
//                Filter::WEEK->value => ProductResource::collection($user->products()->expireThisWeek()->orderedByClosestExpirationDate()->get()),
//                Filter::MONTH->value => ProductResource::collection($user->products()->expireThisMonth()->orderedByClosestExpirationDate()->get()),
//                default => $request->get('filter'),
//            };
//        }

        return ProductResource::collection($user->products()->get());
    }

    public function store(StoreProductRequest $request): ProductResource
    {
        $product = Product::make($request->validated());

        DB::transaction(function () use ($product, $request) {
            $product->save();

            $expirationDates = $request->validated()['expiration_dates'];

            if (!empty($expirationDates)) {
                foreach ($expirationDates as $expirationDate) {
                    ExpirationDate::create(['product_id' => $product->id, 'date' => $expirationDate['date']]);
                }
            }
        });

        return ProductResource::make($product->load('expirationDates'));
    }

    public function show(Request $request, Product $product): ProductResource
    {
        throw_if($product->user()->first()->id !== Auth::user()->id, NotFoundHttpException::class);

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
