<?php

namespace Modules\Core\Providers;

use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app['validator']->extend('en_alpha_numeric_dash', function ($attr, $value, $params) {
            return preg_match('/^[\w-]*$/', $value);
        });

        $this->app['validator']->extend('phone_number_mobile', function ($attribute, $value, $parameters) {
            return preg_match('/09\d{9}/', $value);
        });
    }
}
