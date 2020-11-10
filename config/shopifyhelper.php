<?php

return [
    ###################
    ##### Routing #####
    ###################
    'prefix' => 'shopify/login',
    'middleware' => ['web'], // you probably want to include 'web' here
    'redirect' => 'home', // where to redirect after successful authentication

    #######################
    ##### APP Details #####
    #######################
    'client_id' => env('SHOPIFY_CLIENT_ID', ''),
    'client_secret' => env('SHOPIFY_CLIENT_SECRET', ''),
    'version' => env('SHOPIFY_API_VERSION', now()->startOfQuarter()->format('Y-m')),
    'default_scope' => env('SHOPIFY_APP_SCOPE', 'read_products'),
];
