<?php

namespace Invi5h\LaravelShopify\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Invi5h\LaravelShopify\Database\Factories\ShopifyShopFactory;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ShopifyShop extends Model
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory() : ShopifyShopFactory
    {
        return ShopifyShopFactory::new();
    }
}
