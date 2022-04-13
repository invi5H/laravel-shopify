<?php

namespace Invi5h\LaravelShopify\Contracts;

use Invi5h\LaravelShopify\Contracts\Api\GraphqlClientInterface;
use Invi5h\LaravelShopify\Contracts\Api\ResponseInterface;
use Invi5h\LaravelShopify\Contracts\Api\RestClientInterface;
use Invi5h\LaravelShopify\Contracts\Api\StorefrontClientInterface;
use Invi5h\LaravelShopify\Support\ShopifyAppContext;

interface ShopifyServiceInterface extends RestClientInterface
{
    public function graphql(string $query, array $variables = [], array $headers = []) : ResponseInterface;

    public function storefront(string $query, array $variables = [], array $headers = []) : ResponseInterface;

    public function getRestClient() : RestClientInterface;

    public function getGraphqlClient() : GraphqlClientInterface;

    public function getStorefrontClient() : StorefrontClientInterface;

    public function setContext(ShopifyAppContext $context) : static;
}
