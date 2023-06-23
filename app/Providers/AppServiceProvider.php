<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\ShoppingCartController;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(ShoppingCartController::class, function ($app) {
            return new ShoppingCartController();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(ShoppingCartController $shoppingCartController)
    {
        view()->composer('*', function ($view) {
            $shoppingCartController = new ShoppingCartController();
            $view->with('userDetail', Auth::user());
            $view->with('totalCart', $shoppingCartController->countCart());
            $view->with('userCartHotelIDs', $shoppingCartController->getHostelId_InCart());
        });

        // $totalCart = $shoppingCartController->countCart();
        // View::share('totalCart', $totalCart);
    }
}