<?php

use Invi5h\LaravelShopify\Contracts\Api\GraphqlClientInterface;
use Invi5h\LaravelShopify\Contracts\Api\RestClientInterface;
use Invi5h\LaravelShopify\Contracts\Api\RestResponseInterface;
use Invi5h\LaravelShopify\Contracts\Api\StorefrontClientInterface;
use Invi5h\LaravelShopify\Facades\Shopify;
use Invi5h\LaravelShopify\Models\ShopifyShop;
use Invi5h\LaravelShopify\Service\ShopifyService;
use Invi5h\LaravelShopify\Support\ShopifyAppContext;

it('has facade resolving to correct class', function () : void {
    expect(Shopify::getFacadeRoot())->toBeInstanceOf(ShopifyService::class);
});

it('resolves to correct http clients', function () : void {
    $class = config('laravelshopify.shop_model');

    /** @var ShopifyShop $model */
    $model = $class::factory()->create();
    $shopify = Shopify::setContext(new ShopifyAppContext($model));
    expect($shopify->getRestClient())->toBeInstanceOf(RestClientInterface::class);
    expect($shopify->getGraphqlClient())->toBeInstanceOf(GraphqlClientInterface::class);
    expect($shopify->getStorefrontClient())->toBeInstanceOf(StorefrontClientInterface::class);
});

it('queries valid data', function () : void {
    $class = config('laravelshopify.shop_model');

    /**
     * @var ShopifyShop $model
     * @psalm-suppress UndefinedClass
     */
    $model = new $class([
            'url' => $_ENV['STORE_URL'],
            'access_token' => $_ENV['STORE_ACCESSTOKEN'],
    ]);
    $shopify = Shopify::setContext(new ShopifyAppContext($model));
    $response = $shopify->get('customers', ['limit' => 1]);
    $next = $response->getNextPage();

    expect($response)->toBeInstanceOf(RestResponseInterface::class);
    expect($next)->toBeInstanceOf(RestResponseInterface::class);

    expect($response->hasPagination())->toBeTrue();
    expect($response->hasPreviousPage())->toBeFalse();
    expect($next->hasPagination())->toBeTrue();

    expect($response->getNextPage())->toBe($next);
    expect($next->getPreviousPage())->toBe($response);
    expect($response->getPreviousPage())->toBeNull();

    $next->clearPageReferences();
    expect($next->getPreviousPage()->json())->toBe($response->json());

    $response = $shopify->get('customers', ['limit' => 250]);
    expect($response->getNextPage())->toBeNull();
});

it('works with crud', function () : void {
    $class = config('laravelshopify.shop_model');

    /**
     * @var ShopifyShop $model
     * @psalm-suppress UndefinedClass
     */
    $model = new $class([
            'url' => $_ENV['STORE_URL'],
            'access_token' => $_ENV['STORE_ACCESSTOKEN'],
    ]);
    $shopify = Shopify::setContext(new ShopifyAppContext($model));

    $response = $shopify->post('customers', [
            'first_name' => 'Steve',
            'last_name' => 'Lastname',
            'email' => 'steve.lastname@example.com',
    ]);
    expect($response)->toBeInstanceOf(RestResponseInterface::class);

    $customer = $response->json('customer.id');
    expect($customer)->toBeNumeric();

    $response = $shopify->put("customers/{$customer}", ['last_name' => 'LastName']);
    expect($response)->toBeInstanceOf(RestResponseInterface::class);
    expect($response->json('customer.last_name'))->toBe('LastName');

    $response = $shopify->delete("customers/{$customer}");
    expect($response)->toBeInstanceOf(RestResponseInterface::class);
})->skip(message: 'Needs the write access scope');
