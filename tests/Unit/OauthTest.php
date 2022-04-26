<?php

use Invi5h\LaravelShopify\Controllers\LaravelShopifyController;
use Invi5h\LaravelShopify\Database\Factories\ShopifyShopFactory;
use Invi5h\LaravelShopify\Models\ShopifyShop;
use Invi5h\LaravelShopify\Support\Socialite\ConfigRetriever;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Manager\Config;
use SocialiteProviders\Shopify\Provider;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

it('is valid driver', function () : void {
    $driver = (new LaravelShopifyController())->getOauthDriver();
    expect($driver)->toBeInstanceOf(Provider::class);

    expect(fn() => Socialite::driver('fake'))->toThrow(InvalidArgumentException::class);

    expect(fn() => Socialite::driver('github'))->toThrow(ErrorException::class);

    expect((new ConfigRetriever())->fromServices('fake'))->toBeInstanceOf(Config::class);
});

it('is valid oauth response', function () : void {
    $class = config('laravelshopify.shop_model');

    /** @psalm-suppress UndefinedClass */
    $model = new $class();

    $controller = new LaravelShopifyController();
    $class = new ReflectionClass($controller);
    $method = $class->getMethod('getOauthResponse');
    $method->setAccessible(true);

    $response = $method->invoke($controller, $model->requiredScopes()->join(','));
    expect($response)->toBeResponse();
});

it('is valid view', function () : void {
    $controller = new LaravelShopifyController();
    $class = new ReflectionClass($controller);

    $method = $class->getMethod('redirectToAppView');
    $method->setAccessible(true);
    expect(fn() : mixed => $method->invoke($controller))->toThrow(RouteNotFoundException::class, 'Route [home] not defined.');

    $method = $class->getMethod('renderAccessDeniedView');
    $method->setAccessible(true);
    expect(fn() : mixed => $method->invoke($controller))->toThrow(HttpException::class);

    $method = $class->getMethod('billingPageRedirectResponse');
    $method->setAccessible(true);
    $class = config('laravelshopify.shop_model');

    /** @psalm-suppress UndefinedClass */
    expect($method->invoke($controller, new $class()))->toBeResponse();
});

it('is valid app url', function () : void {
    $class = config('laravelshopify.shop_model');
    $factory = $class::factory();
    expect($factory)->toBeInstanceOf(ShopifyShopFactory::class);

    /** @var ShopifyShop $model */
    $model = $factory->create();

    $controller = new LaravelShopifyController();
    $class = new ReflectionClass($controller);
    $method = $class->getMethod('getAppUrl');
    $method->setAccessible(true);

    $url = $method->invoke($controller, $model);
    expect($url)->toContain($model->url);
});
