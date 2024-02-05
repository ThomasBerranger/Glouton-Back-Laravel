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
    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $user = Auth::user();

        if ($request->has('filter') && array_key_exists('expiration_dates', $request->get('filter'))) {
            if ($request->get('filter')['expiration_dates'] === Filter::WEEK->value) {
                return ProductResource::collection(
                    $user->products()->notFinished()->week()->get()
                );
            } else if ($request->get('filter')['expiration_dates'] === Filter::MONTH->value) {
                return ProductResource::collection(
                    $user->products()->notFinished()->month()->get()
                );
            } else if ($request->get('filter')['expiration_dates'] === Filter::YEARS->value) {
                return ProductResource::collection(
                    $user->products()->notFinished()->years()->get()
                );
            } else if ($request->get('filter')['expiration_dates'] === Filter::FINISHED->value) {
                return ProductResource::collection(
                    $user->products()->finished()->orderedBy('finished_at', false)->get()
                );
            }
        }

        return ProductResource::collection($user->products()->withMinExpirationDate()->orderedBy('closest_expiration_date')->get());
    }

    public function store(StoreProductRequest $request): ProductResource
    {
        $product = Product::make($request->validated());

        DB::transaction(function () use ($product, $request) {
            $product->save();

            $expirationDates = $request->validated()['expiration_dates'];

            if (!empty($expirationDates) and !empty($expirationDates[0])) {
                foreach ($expirationDates as $expirationDate) {
                    ExpirationDate::create(['product_id' => $product->id, 'date' => $expirationDate['date']]);
                }
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
