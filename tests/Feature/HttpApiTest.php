<?php

use Illuminate\Contracts\Container\BindingResolutionException;
use Invi5h\LaravelShopify\Contracts\Api\GraphqlClientInterface;
use Invi5h\LaravelShopify\Contracts\Api\ResponseInterface;
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

    expect(fn() => Shopify::getRestClient())->toThrow(BindingResolutionException::class);

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

    $response = $shopify->post('products', [
            'product' => [
                    'title' => 'Test Item',
                    'vendor' => 'test',
                    'product_type' => 'test',
                    'body_html' => '<p>test body</p>',
            ],
    ]);
    expect($response)->toBeInstanceOf(RestResponseInterface::class);

    $product = $response->json('product.id');
    expect($product)->toBeNumeric();

    $response = $shopify->put("products/{$product}", ['product' => ['body_html' => '<p>updated body</p>']]);
    expect($response)->toBeInstanceOf(RestResponseInterface::class);
    expect($response->json('product.body_html'))->toBe('<p>updated body</p>');

    $query = <<<'QUERY'
      query($id: ID!) {
        product(id: $id) {
          title
        }
      }
    QUERY;
    $response = $shopify->graphql($query, ['id' => $response->json('product.admin_graphql_api_id')]);
    expect($response)->toBeInstanceOf(ResponseInterface::class);
    expect($response->json('data.product.title'))->toBe('Test Item');

    $response = $shopify->delete("products/{$product}");
    expect($response)->toBeInstanceOf(RestResponseInterface::class);
});
