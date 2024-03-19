<?php

namespace App\Policies;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RecipePolicy
{
    public function viewAny(User $user): true
    {
        return true;
    }

    public function create(User $user): true
    {
        return true;
    }

    public function delete(User $user, Recipe $recipe): Response
    {
        return $user->id === $recipe->user->id ? Response::allow() : Response::denyWithStatus(404);
    }
}
