<?php

use Invi5h\LaravelShopify\Controllers\LaravelShopifyController;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Shopify\Provider;

it('is valid driver', function () : void {
    $driver = (new LaravelShopifyController())->getOauthDriver();
    expect($driver)->toBeInstanceOf(Provider::class);

    expect(fn() => Socialite::driver('fake'))->toThrow(InvalidArgumentException::class);

    expect(fn() => Socialite::driver('github'))->toThrow(ErrorException::class);
});
