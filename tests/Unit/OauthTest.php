<?php

use Invi5h\LaravelShopify\Controllers\LaravelShopifyController;
use SocialiteProviders\Shopify\Provider;

it('is valid driver', function () : void {
    $driver = (new LaravelShopifyController())->getOauthDriver();
    expect($driver)->toBeInstanceOf(Provider::class);
});
