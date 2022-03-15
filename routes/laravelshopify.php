<?php

use Illuminate\Support\Facades\Route;
use Invi5h\LaravelShopify\Controllers\LaravelShopifyController;

Route::get('/', [LaravelShopifyController::class, 'home'])->name('laravelshopify.home');
Route::get('/callback', [LaravelShopifyController::class, 'callback'])->name('laravelshopify.callback');
