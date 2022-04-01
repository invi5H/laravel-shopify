<?php

use Illuminate\Database\Eloquent\Model;
use Invi5h\LaravelShopify\Contracts\ShopModelInterface;
use Invi5h\LaravelShopify\Models\AppType;
use Invi5h\LaravelShopify\Models\Billing\BillingType;

it('is valid eloquent model', function () : void {
    $class = config('laravelshopify.shop_model');

    /** @psalm-suppress UndefinedClass */
    $object = new $class();
    expect($object)->toBeInstanceOf(Model::class);
    expect($object)->toBeInstanceOf(ShopModelInterface::class);
});

it('is valid app type', function () : void {
    $type = config('laravelshopify.type');
    expect($type)->toBeInstanceOf(AppType::class);
});

it('is valid billing type', function () : void {
    $type = config('laravelshopify.billing_type');
    expect($type)->toBeInstanceOf(BillingType::class);
});
