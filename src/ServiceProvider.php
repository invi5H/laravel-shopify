<?php

namespace Invi5h\LaravelShopify;

use Firebase\JWT\Key;
use Illuminate\Auth\RequestGuard;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Invi5h\LaravelShopify\Api\Graphql;
use Invi5h\LaravelShopify\Api\Rest;
use Invi5h\LaravelShopify\Api\Storefront;
use Invi5h\LaravelShopify\Contracts\Api\GraphqlClientInterface;
use Invi5h\LaravelShopify\Contracts\Api\RestClientInterface;
use Invi5h\LaravelShopify\Contracts\Api\StorefrontClientInterface;
use Invi5h\LaravelShopify\Contracts\ShopifyServiceInterface;
use Invi5h\LaravelShopify\Service\ShopifyService;
use Invi5h\LaravelShopify\Support\Auth\ShopifyGuard;
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
     *
     * @psalm-suppress MissingClosureParamType
     * @psalm-suppress UnusedClosureParam
     * @psalm-suppress MixedArrayAccess
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

        $this->app->bind(ShopifyServiceInterface::class, ShopifyService::class);
        $this->app->bind(RestClientInterface::class, Rest::class);
        $this->app->bind(GraphqlClientInterface::class, Graphql::class);
        $this->app->bind(StorefrontClientInterface::class, Storefront::class);

        Auth::extend('shopify', function ($app, $name, array $config) : RequestGuard {
            return tap($this->createShopifyGuard($config, $app['request']), function ($guard) use ($app) : void {
                $app->refresh('request', $guard, 'setRequest');
            });
        });
    }

    protected function createShopifyGuard(array $config, mixed $request) : RequestGuard
    {
        $key = new Key(config('laravelshopify.api_secret'), 'HS256');

        return new RequestGuard(new ShopifyGuard(config('laravelshopify.api_key'), $key, $config['leeway'] ?? null), $request);
    }

    private function collectionMacros() : void
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
            $keys = $this->keys()->map($callback);
            /** @psalm-suppress InvalidArgument */
            return $keys->combine($this->values());
        });
    }
}
