<?php

namespace Invi5h\LaravelShopify\Service;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Traits\ForwardsCalls;
use Invi5h\LaravelShopify\Contracts\Api\GraphqlClientInterface;
use Invi5h\LaravelShopify\Contracts\Api\ResponseInterface;
use Invi5h\LaravelShopify\Contracts\Api\RestClientInterface;
use Invi5h\LaravelShopify\Contracts\Api\RestResponseInterface;
use Invi5h\LaravelShopify\Contracts\Api\StorefrontClientInterface;
use Invi5h\LaravelShopify\Contracts\ShopifyServiceInterface;
use Invi5h\LaravelShopify\Support\ShopifyAppContext;

class ShopifyService implements ShopifyServiceInterface
{
    use ForwardsCalls;

    protected ShopifyAppContext $context;

    public function getRestClient() : RestClientInterface
    {
        return App::makeWith(RestClientInterface::class, ['url' => $this->context->url, 'accessToken' => $this->context->access_token]);
    }

    public function getGraphqlClient() : GraphqlClientInterface
    {
        return App::makeWith(GraphqlClientInterface::class, ['url' => $this->context->url, 'accessToken' => $this->context->access_token]);
    }

    public function getStorefrontClient() : StorefrontClientInterface
    {
        return App::makeWith(StorefrontClientInterface::class, ['url' => $this->context->url, 'accessToken' => $this->context->storefront_token]);
    }

    public function setContext(ShopifyAppContext $context) : static
    {
        $this->context = $context;

        return $this;
    }

    public function get(string $resource, array $params = [], array $headers = []) : RestResponseInterface
    {
        return $this->forwardCallTo($this->getRestClient(), __FUNCTION__, [$resource, $params, $headers]);
    }

    public function post(string $resource, array $params = [], array $headers = []) : RestResponseInterface
    {
        return $this->forwardCallTo($this->getRestClient(), __FUNCTION__, [$resource, $params, $headers]);
    }

    public function put(string $resource, array $params = [], array $headers = []) : RestResponseInterface
    {
        return $this->forwardCallTo($this->getRestClient(), __FUNCTION__, [$resource, $params, $headers]);
    }

    public function delete(string $resource, array $params = [], array $headers = []) : RestResponseInterface
    {
        return $this->forwardCallTo($this->getRestClient(), __FUNCTION__, [$resource, $params, $headers]);
    }

    public function graphql(string $query, array $variables = [], array $headers = []) : ResponseInterface
    {
        return $this->forwardCallTo($this->getGraphqlClient(), 'send', [$query, $variables, $headers]);
    }

    public function storefront(string $query, array $variables = [], array $headers = []) : ResponseInterface
    {
        return $this->forwardCallTo($this->getStorefrontClient(), 'send', [$query, $variables, $headers]);
    }
}
