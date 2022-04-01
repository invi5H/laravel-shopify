<?php

use Invi5h\LaravelShopify\Controllers\LaravelShopifyController;
use Invi5h\LaravelShopify\Support\Socialite\ConfigRetriever;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Manager\Config;
use SocialiteProviders\Shopify\Provider;

it('is valid driver', function () : void {
    $driver = (new LaravelShopifyController())->getOauthDriver();
    expect($driver)->toBeInstanceOf(Provider::class);

    expect(fn() => Socialite::driver('fake'))->toThrow(InvalidArgumentException::class);

    expect(fn() => Socialite::driver('github'))->toThrow(ErrorException::class);

    expect((new ConfigRetriever())->fromServices('fake'))->toBeInstanceOf(Config::class);
});
