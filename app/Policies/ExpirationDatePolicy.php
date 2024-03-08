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
        $expirationDateRelatedProduct = Product::find(request()->product_id);

        return $expirationDateRelatedProduct ?
            self::isCurrentUserRelatedToExpirationDateProduct($user, $expirationDateRelatedProduct) :
            Response::denyWithStatus(404, 'Product not found.');
    }

//    public function update(User $user, ExpirationDate $expirationDate): Response
//    {
//        return $user->id === $expirationDate->product->user->id ? Response::allow() : Response::denyWithStatus(404);
//    }

//    public function delete(User $user, ExpirationDate $expirationDate): Response
//    {
//        return $user->id === $expirationDate->product->user->id ? Response::allow() : Response::denyWithStatus(404);
//    }

//    public function restore(User $user, ExpirationDate $expirationDate): bool
//    {
//    }

//    public function forceDelete(User $user, ExpirationDate $expirationDate): bool
//    {
//    }

    private function isCurrentUserRelatedToExpirationDateProduct(User $currentUser, Product $product): Response
    {
        return $currentUser->id === $product->user->id ? Response::allow() : Response::denyWithStatus(404, 'Product not found.');
    }
}
