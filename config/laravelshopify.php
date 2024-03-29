<?php

use Illuminate\Support\Str;
use Invi5h\LaravelShopify\Models\AppType;
use Invi5h\LaravelShopify\Models\Billing\BillingType;
use Invi5h\LaravelShopify\Models\ShopifyShop;

return [
    // shop eloquent model, should implement ShopModelInterface
    'shop_model' => ShopifyShop::class,

    // the app details
    'type' => AppType::tryFrom(env('APP_TYPE', 'public')) ?: AppType::PUBLIC,

    'name' => env('APP_NAME', 'App'),
    'url' => env('APP_SHOPIFY_URL', Str::slug(env('APP_NAME', 'App'))),

    // fixed subdomain is for custom apps
    'subdomain' => env('APP_SUBDOMAIN', ''),
    'api_key' => env('APP_ACCESS_KEY', ''),
    'api_secret' => env('APP_SECRET', ''),
    'api_version' => env('APP_VERSION', now()->startOfQuarter()->format('Y-m')),

    // dev stores are always in test mode
    'test_mode' => (bool) env('TEST_MODE', false),
    'default_scopes' => env('APP_SCOPE') ? array_filter(array_map('trim', explode(',', env('APP_SCOPE')))) : ['read_customers'],

    'billing_type' => BillingType::tryFrom(env('BILLING_TYPE', 'onetime')) ?: BillingType::ONETIME,
    'billing_name' => env('BILLING_NAME', env('APP_NAME', 'App')),
    'billing_amount' => (float) env('BILLING_PRICE', 0),
    'billing_currency' => env('BILLING_CURRENCY', 'USD'),
    // following only apply to recurring billing
    'trial_days' => (int) env('TRIAL_DAYS', 15),
    'annual' => env('BILLING_ANNUAL', false),
    'usage_cap' => (float) env('BILLING_CAP', 0),
    'usage_terms' => env('BILLING_TERMS', 'based on usage'),
];
