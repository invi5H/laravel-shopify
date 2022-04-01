<?php

namespace Invi5h\LaravelShopify\Tests;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Invi5h\LaravelShopify\ServiceProvider;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 * @internal
 */
class TestCase extends \Orchestra\Testbench\TestCase
{
    use LazilyRefreshDatabase;

    protected function getPackageProviders($app)
    {
        return [
                ServiceProvider::class,
        ];
    }
}
