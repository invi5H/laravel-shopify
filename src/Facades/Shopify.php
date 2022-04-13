<?php

namespace Invi5h\LaravelShopify\Facades;

use Illuminate\Support\Facades\Facade;
use Invi5h\LaravelShopify\Contracts\ShopifyServiceInterface;

class Shopify extends Facade
{
    protected static function getFacadeAccessor() : string
    {
        return ShopifyServiceInterface::class;
    }
}
