<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Filament\Facades\Filament;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\Builder;
use Illuminate\Support\Facades\URL;

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
        if (App::isProduction()) {
            URL::forceScheme('https');
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
