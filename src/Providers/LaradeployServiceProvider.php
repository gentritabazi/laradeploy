<?php

namespace GentritAbazi\Laradeploy\Providers;

use Illuminate\Support\ServiceProvider;

class LaradeployServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__. '../../Config/laradeploy.php',
            'laradeploy'
        );
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__. '/../Config/laradeploy.php' => config_path('laradeploy.php'),
        ]);

        $this->loadRoutesFrom(__DIR__. '/../Routes/web.php');
    }
}
