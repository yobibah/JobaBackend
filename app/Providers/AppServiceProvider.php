<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

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
        //
            Route::middleware('api')
    ->prefix('api')
    ->group(base_path('routes/api.php'));
    }

 

}
