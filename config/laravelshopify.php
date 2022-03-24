<?php

use Illuminate\Support\Str;
use Invi5h\LaravelShopify\Models\AppType;
use Invi5h\LaravelShopify\Models\BillingType;
use Invi5h\LaravelShopify\Models\ShopifyShop;

return [
    // shop eloquent model, should implement ShopModelInterface
    'shop_model' => ShopifyShop::class,

    // the app details
    'type' => AppType::PUBLIC,
    'name' => env('APP_NAME', 'App'),
    'url' => env('APP_URL', Str::slug(env('APP_NAME', 'App'))),
    'api_key' => env('API_KEY', ''),
    'api_secret' => env('API_SECRET', ''),
    // dev stores are always in test mode
    'test_mode' => (bool) env('TEST_MODE', false),
    'default_scopes' => env('APP_SCOPE') ? array_filter(array_map('trim', explode(',', env('APP_SCOPE')))) : ['read_customers'],

    'billing_type' => BillingType::ONETIME,
    'billing_name' => env('BILLING_NAME', env('APP_NAME', 'App')),
    'billing_amount' => (float) env('BILLING_PRICE', 0),
    'billing_currency' => env('BILLING_CURRENCY', 'USD'),
    // following only apply to recurring billing
    'trial_days' => (int) env('TRIAL_DAYS', 15),
    'annual' => env('BILLING_ANNUAL', false),
    'usage_cap' => (float) env('BILLING_CAP', 0),
    'usage_terms' => env('BILLING_TERMS', 'based on usage'),
];
