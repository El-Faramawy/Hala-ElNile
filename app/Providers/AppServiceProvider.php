<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\City;
use Illuminate\Support\Facades\View;
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
        // Ensure Egyptian governorates are always provided to Site views and modals
        View::composer(['Site.*', 'components.site.*', 'layouts.site.*'], function ($view): void {
            if (! $view->offsetExists('cities')) {
                $view->with('cities', City::where('is_active', true)->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get());
            }
        });
    }
}
