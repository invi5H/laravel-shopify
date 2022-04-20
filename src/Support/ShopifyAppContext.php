<?php

namespace Invi5h\LaravelShopify\Support;

use Invi5h\LaravelShopify\Contracts\ShopModelInterface;
use Invi5h\LaravelShopify\Models\ShopifyShop;

/**
 * @property string $url
 * @property string $access_token
 * @property string $storefront_token
 */
class ShopifyAppContext
{
    public function __construct(protected ShopModelInterface $shop)
    {
    }

    public function __get(string $name)
    {
        /** @var ShopifyShop $shop */
        $shop = $this->shop;

        return match ($name) {
            'url' => $shop->url,
            'access_token' => $shop->access_token,
            'storefront_token' => $shop->storefront_token,
        };
    }
}
