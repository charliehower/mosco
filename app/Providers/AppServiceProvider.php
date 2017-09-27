<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(config('app.env')=='production'){
            \URL::forceSchema('https');
            $this->app['request']->server->set('HTTPS','on');
            error_reporting(E_ALL ^ E_DEPRECATED);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
