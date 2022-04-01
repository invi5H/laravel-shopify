<?php

use Illuminate\Database\Eloquent\Model;
use Invi5h\LaravelShopify\Contracts\ShopModelInterface;

it('is valid eloquent model', function () : void {
    $class = config('laravelshopify.shop_model');

    /** @psalm-suppress UndefinedClass */
    $object = new $class();
    expect($object)->toBeInstanceOf(Model::class);
    expect($object)->toBeInstanceOf(ShopModelInterface::class);
});
