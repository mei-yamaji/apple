<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.navigation', function ($view) {
            if (Auth::check()) {
                $notifications = Auth::user()->notifications()->latest()->take(20)->get();
                $view->with('notifications', $notifications);
            } else {
                $view->with('notifications', collect()); // 未ログイン時は空コレクションを渡す
            }
        });
    }
}
