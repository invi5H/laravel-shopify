<?php

namespace Invi5h\LaravelShopify\Api;

use Illuminate\Http\Client\PendingRequest;
use Invi5h\LaravelShopify\Contracts\Api\StorefrontClientInterface;

class Storefront extends Graphql implements StorefrontClientInterface
{
    protected function newClient(array $options = []) : PendingRequest
    {
        $url = "https://{$this->domain}/api/".config('laravelshopify.api_version');

        return $this->makeHttpClient($options)->baseUrl($url)->withHeaders(['X-Shopify-Storefront-Access-Token' => $this->accessToken]);
    }
}
