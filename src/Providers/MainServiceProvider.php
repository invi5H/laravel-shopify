<?php

namespace Invi5h\ShopifyHelper\Providers;

use Illuminate\Support\ServiceProvider;

class MainServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(dirname(dirname(__DIR__)).'/config/shopifyhelper.php', 'shopifyhelper');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                    __DIR__.'/../../config/shopifyhelper.php' => config_path('shopifyhelper.php'),
            ], 'config');
        }
    }
}
