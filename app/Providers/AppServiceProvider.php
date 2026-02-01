<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // if (app()->environment('production')) {
        //     URL::forceScheme('https');
        // }
        // Route model binding untuk Kelas
        Route::model('kelas', \App\Models\Kelas::class);

        // Custom middleware untuk role-based access
        Route::aliasMiddleware('role', \App\Http\Middleware\CheckRole::class);

        Model::automaticallyEagerLoadRelationships();
    }
}
