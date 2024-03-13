<?php

namespace App\Policies;

use App\Models\User;

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
}
