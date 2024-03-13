<?php

namespace App\Observers;

use App\Models\Recipe;
use Illuminate\Support\Facades\Auth;

class RecipeObserver
{
    public function creating(Recipe $recipe): void
    {
        $recipe->user()->associate(Auth::user());
    }
}
