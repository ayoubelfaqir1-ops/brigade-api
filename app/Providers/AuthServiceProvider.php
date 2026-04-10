<?php

namespace App\Providers;

use App\Models\Plat;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Recommendation;
use App\Policies\PlatPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\IngredientPolicy;
use App\Policies\RecommendationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Plat::class => PlatPolicy::class,
        Category::class => CategoryPolicy::class,
        Ingredient::class => IngredientPolicy::class,
        Recommendation::class => RecommendationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
