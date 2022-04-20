<?php

namespace Invi5h\LaravelShopify\Tests;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Invi5h\LaravelShopify\ServiceProvider;
use Laravel\Socialite\SocialiteServiceProvider;
use SocialiteProviders\Manager\ServiceProvider as SocialiteShopifyServiceProvider;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 *
 * @internal
 */
class TestCase extends \Orchestra\Testbench\TestCase
{
    use LazilyRefreshDatabase;

    protected function getPackageProviders($app)
    {
        return [
                SocialiteServiceProvider::class,
                SocialiteShopifyServiceProvider::class,
                ServiceProvider::class,
        ];
    }
}
