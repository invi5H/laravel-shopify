<?php

namespace Invi5h\ShopifyHelper\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Support\Facades\Route;
use SocialiteProviders\Manager\SocialiteWasCalled;

class MainServiceProvider extends EventServiceProvider
{
    protected $listen = [
            SocialiteWasCalled::class => [
                    'SocialiteProviders\\Shopify\\ShopifyExtendSocialite@handle',
            ],
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(dirname(dirname(__DIR__)).'/config/shopifyhelper.php', 'shopifyhelper');

        config([
                'services.shopify' => array_merge_recursive([
                        'client_id' => config('shopifyhelper.client_id'),
                        'client_secret' => config('shopifyhelper.client_secret'),
                        'redirect' => route('shopify.login.callback')
                ], config('services.shopify', []))
        ]);

        parent::register();
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

        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->registerRoutes();
    }

    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        });
    }

    protected function routeConfiguration()
    {
        return [
                'prefix' => config('shopifyhelper.prefix'),
                'middleware' => config('shopifyhelper.middleware'),
        ];
    }
}
