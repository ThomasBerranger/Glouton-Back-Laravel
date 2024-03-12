<?php

namespace App\Policies;

use App\Models\ExpirationDate;
use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExpirationDatePolicy
{
    //    public function viewAny(User $user): true
    //    {
    //        return true;
    //    }

    //    public function view(User $user, ExpirationDate $expirationDate): Response
    //    {
    //        return $user->id === $expirationDate->product->user->id ? Response::allow() : Response::denyWithStatus(404);
    //    }

    public function create(User $user): Response
    {
        $productId = request()->product_id;

        if (! is_string($productId)) {
            return Response::denyWithStatus(422, 'product_id must be a string.');
        }

        $expirationDateRelatedProduct = Product::find($productId);

        return self::isCurrentUserRelatedToExpirationDateProduct($user, $expirationDateRelatedProduct);
    }

    public function update(User $user, ExpirationDate $expirationDate): Response
    {
        return self::isCurrentUserRelatedToExpirationDateProduct($user, $expirationDate->product);
    }

    public function delete(User $user, ExpirationDate $expirationDate): Response
    {
        return self::isCurrentUserRelatedToExpirationDateProduct($user, $expirationDate->product);
    }

    //    public function restore(User $user, ExpirationDate $expirationDate): bool
    //    {
    //    }

    //    public function forceDelete(User $user, ExpirationDate $expirationDate): bool
    //    {
    //    }

    private function isCurrentUserRelatedToExpirationDateProduct(User $currentUser, ?Product $product): Response
    {
        return $product && $currentUser->id === $product->user?->id ? Response::allow() : Response::denyWithStatus(404, 'Product not found.');
    }
}
