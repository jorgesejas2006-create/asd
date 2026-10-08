<?php

namespace App\Providers;

use App\Models\Juego;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Password::defaults(fn () => Password::min(8)
            ->letters()
            ->mixedCase()
            ->numbers()
            ->symbols());

        View::composer('layouts.app', function ($view) {
            $alertasStockAdmin = collect();

            if (Auth::check() && Auth::user()->rol === 'admin') {
                $alertasStockAdmin = Juego::where('en_venta', true)
                    ->where('stock', '<=', Juego::STOCK_BAJO)
                    ->orderBy('stock')
                    ->orderBy('nombre')
                    ->get(['id', 'nombre', 'stock']);
            }

            $view->with('alertasStockAdmin', $alertasStockAdmin);
        });
    }
}
