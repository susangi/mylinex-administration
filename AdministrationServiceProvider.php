<?php

namespace Administration;

use Illuminate\Support\ServiceProvider;

class AdministrationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Administration', function($app){
            return new Administration;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/web.php');
        $this->loadViewsFrom(__DIR__.'/Views', 'Administration');
    }
}
