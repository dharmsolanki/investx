<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Storage symlink auto-create
        $link   = public_path('storage');
        $target = storage_path('app/public');

        if (! file_exists($link) && file_exists($target)) {
            symlink($target, $link);
        }
    }
}
