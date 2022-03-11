<?php

use Illuminate\Support\Facades\Route;
use Invi5h\LaravelShopify\Controllers\LaravelShopifyController;

Route::get('/', [LaravelShopifyController::class, 'redirectToShopify'])->name('laravelshopify.home');
Route::get('/callback', [LaravelShopifyController::class, 'handleShopifyCallback'])->name('laravelshopify.callback');
