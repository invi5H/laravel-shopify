<?php

use Invi5h\LaravelShopify\Database\Factories\ShopifyShopFactory;
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

    $factory = $class::factory();
    expect($factory)->toBeInstanceOf(ShopifyShopFactory::class);

    /** @var ShopifyShop $model */
    $model = $factory->create();

    mock($model)->makePartial()->shouldRecieve('requiredScopes')->andReturn('read_fake');
})->only();
