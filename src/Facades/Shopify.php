<?php

namespace Invi5h\LaravelShopify\Facades;

use Illuminate\Support\Facades\Facade;
use Invi5h\LaravelShopify\Contracts\ShopifyServiceInterface;
use Invi5h\LaravelShopify\Service\ShopifyService;
use Invi5h\LaravelShopify\Support\ShopifyAppContext;

/**
 * @method static ShopifyService setContext(ShopifyAppContext $context)
 * @mixin ShopifyService
 */
class Shopify extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return ShopifyServiceInterface::class;
    }
}
