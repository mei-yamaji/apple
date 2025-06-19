<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * 通常ログインユーザーのリダイレクト先
     */
    public const HOME = '/';

    /**
     * 管理者ログイン後のリダイレクト先
     */
    public const ADMIN_HOME = '/admin';

    /**
     * アプリケーションのルートをマップする
     */
    public function boot(): void
    {
        $this->routes(function () {
            // Webルート
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // APIルート
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));
        });
    }
}