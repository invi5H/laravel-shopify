<?php

use Illuminate\Support\Facades\Event;
use Invi5h\LaravelShopify\Database\Factories\ShopifyShopFactory;
use Invi5h\LaravelShopify\Events\ShopifyInstallEvent;
use Invi5h\LaravelShopify\Models\ShopifyShop;

it('has correct factory class', function () : void {
    $class = config('laravelshopify.shop_model');

    $factory = $class::factory();
    expect($factory)->toBeInstanceOf(ShopifyShopFactory::class);

    /** @var ShopifyShop $model */
    $model = $factory->create();
    expect($model)->toBeInstanceOf(ShopifyShop::class);
    expect($model->url)->toBeString();
});

it('supports reauth', function () : void {
    $class = config('laravelshopify.shop_model');

    $mock = mock($class)->expect(requiredScopes: fn() => collect(['read_fake']))->makePartial();
    expect($mock->needsReauth())->toBeTrue();

    $mock = mock($class)->expect(requiredScopes: fn() => collect())->makePartial();
    expect($mock->needsReauth())->toBeFalse();
});

it('sends install event', function () : void {
    $class = config('laravelshopify.shop_model');
    $factory = $class::factory();
    /** @var ShopifyShop $model */
    $model = $factory->create();

    Event::fake();
    $model->setup();
    Event::assertDispatched(ShopifyInstallEvent::class);
});

it('is connected to shopify', function () : void {
    $class = config('laravelshopify.shop_model');

    /**
     * @var ShopifyShop $model
     * @psalm-suppress UndefinedClass
     */
    $model = new $class([
            'url' => env('STORE_URL'),
            'access_token' => env('STORE_ACCESSTOKEN'),
    ]);

    expect(fn() => $model->reloadFromShopify())->not()->toThrow(RuntimeException::class);
    expect($model->isAccessTokenValid())->toBeTrue();
    expect($model->allowedAccess())->toBeTrue();
    expect($model->isDevShop())->toBeTrue();
    expect($model->isPlusShop())->toBeFalse();
    expect($model->needsBilling())->toBeFalse();
    expect($model->hasPendingBillingContract())->toBeFalse();
    expect($model->createBillingContract())->toBeNull();
});
