<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        if($this->app->environment('production')) {
            \URL::forceScheme('https');
        }

        Builder::macro('count', function () {
            return $this->engine()->getTotalCount(
                $this->engine()->search($this)
            );
        });

        Filament::registerNavigationGroups([
            __('Features'),
            __('Authentication'),
            __('Settings'),
        ]);
    }
}
