<?php

use Illuminate\Auth\RequestGuard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth as Auth;
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
    expect($type->isPublic())->toBeTrue();
    expect($type->isCustom())->toBeFalse();
});

it('is valid billing type', function () : void {
    $type = config('laravelshopify.billing_type');
    expect($type)->toBeInstanceOf(BillingType::class);
    expect($type->isOnetime())->toBeTrue();
    expect($type->isRecurring())->toBeFalse();
    expect($type->enabled())->toBeTrue();
    expect($type->disabled())->toBeFalse();
});

it('has valid auth guard', function () : void {
    $this->app['config']->set('auth.guards.shopify', ['driver' => 'shopify']);
    $guard = Auth::guard('shopify');
    expect($guard)->toBeInstanceOf(RequestGuard::class);

    $this->app['config']->set('auth.guards.shopify', ['driver' => 'shopify', 'leeway' => 'invalid']);
    $guard = Auth::guard('shopify');
    expect($guard)->toBeInstanceOf(RequestGuard::class);
});
