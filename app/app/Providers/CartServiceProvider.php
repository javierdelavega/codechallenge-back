<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\CartServiceInterface;
use App\Services\CartService;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CartServiceInterface::class, CartService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

    }
}
