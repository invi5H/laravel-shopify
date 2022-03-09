<?php

namespace Invi5h\LaravelShopify;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        $this->mergeConfigFrom(dirname(__DIR__).'/config/laravelshopify.php', 'laravelshopify');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $paths = [
                    __DIR__.'/../config/laravelshopify.php' => config_path('laravelshopify.php'),
            ];
            $this->publishes($paths, 'laravelshopify');
        }

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/laravelshopify.php');
    }
}
