<?php

namespace App\Providers;

use App\Models\ExpirationDate;
use App\Models\Product;
use App\Models\Recipe;
use App\Policies\ExpirationDatePolicy;
use App\Policies\ProductPolicy;
use App\Policies\RecipePolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Product::class => ProductPolicy::class,
        ExpirationDate::class => ExpirationDatePolicy::class,
        Recipe::class => RecipePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
        //            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        //        });
    }
}
