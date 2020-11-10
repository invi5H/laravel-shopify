<?php

use Illuminate\Support\Facades\Route;
use Invi5h\ShopifyHelper\Http\Controllers\ShopifyController;

Route::get('/', [ShopifyController::class, 'redirectToShopify'])->name('shopify.login');
Route::get('/callback', [ShopifyController::class, 'handleShopifyCallback'])->name('shopify.login.callback');
