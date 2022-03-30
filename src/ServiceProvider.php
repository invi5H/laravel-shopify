<?php

namespace Invi5h\LaravelShopify;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Invi5h\LaravelShopify\Support\Socialite\ConfigRetriever;
use SocialiteProviders\Manager\Contracts\Helpers\ConfigRetrieverInterface;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Shopify\ShopifyExtendSocialite;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register services.
     */
    public function register() : void
    {
        parent::register();

        $this->mergeConfigFrom(dirname(__DIR__).'/config/laravelshopify.php', 'laravelshopify');

        $this->app->singleton(ConfigRetrieverInterface::class, fn() => new ConfigRetriever());
    }

    /**
     * Bootstrap services.
     */
    public function boot() : void
    {
        if ($this->app->runningInConsole()) {
            $paths = [
                    __DIR__.'/../config/laravelshopify.php' => config_path('laravelshopify.php'),
            ];
            $this->publishes($paths, 'laravelshopify');
        }

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/laravelshopify.php');

        Event::listen(SocialiteWasCalled::class, ShopifyExtendSocialite::class);

        $this->collectionMacros();
    }

    public function collectionMacros() : void
    {
        Collection::macro('trim', function () {
            /** @var Collection $this */
            return $this->map(fn($value) => is_string($value) ? trim($value) : $value);
        });
        Collection::macro('trimRecursive', function () {
            /** @var Collection $this */
            return $this->map(function ($value) {
                if (is_string($value)) {
                    return trim($value);
                }
                if (is_array($value)) {
                    return collect($value)->trimRecursive()->all();
                }
                if ($value instanceof Collection) {
                    return $value->trimRecursive();
                }

                return $value;
            });
        });
        Collection::macro('mapKeys', function (callable $callback) {
            /** @var Collection $this */
            /* @psalm-suppress InvalidArgument */
            return $this->keys()->map($callback)->combine($this->values());
        });
    }
}
