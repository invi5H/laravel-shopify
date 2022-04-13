<?php

namespace Invi5h\LaravelShopify\Support;

use Invi5h\LaravelShopify\Models\ShopifyShop;

/**
 * @property string $url
 * @property string $access_token
 * @property string $storefront_token
 */
class ShopifyAppContext
{
    public function __construct(protected ShopifyShop $shop)
    {
    }

    public function __get(string $name)
    {
        return match ($name) {
            'domain' => $this->shop->url,
            'access_token' => $this->shop->access_token,
            'storefront_token' => $this->shop->storefront_token,
        };
    }
}
