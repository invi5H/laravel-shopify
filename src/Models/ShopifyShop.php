<?php

namespace Invi5h\LaravelShopify\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Invi5h\LaravelShopify\Database\Factories\ShopifyShopFactory;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ShopifyShop extends Model
{
    use HasFactory;

    public function setup() : void
    {
        // @todo implement
    }

    public function isAccessTokenValid() : bool
    {
        // @todo implement
        return true;
    }

    public function needsReauth() : bool
    {
        // @todo implement
        return false;
    }

    public function needsBilling() : bool
    {
        // @todo implement
        return false;
    }

    public function hasPendingBillingContract() : bool
    {
        // @todo implement
        return false;
    }

    public function allowedAccess() : bool
    {
        // @todo implement
        return true;
    }

    public function isDevShop() : bool
    {
        // @todo implement
        return true;
    }

    public function createNewRecurringContract(array $params)
    {
        // @todo implement
        return null;
    }

    public function getbillingPageRedirectUrl() : string
    {
        // @todo implement
        return '';
    }

    /**
     * @return Collection<int, string>
     */
    public function requiredScopes() : Collection
    {
        return static::defaultScopes();
    }

    /**
     * @return Collection<int, string>
     */
    public static function defaultScopes() : Collection
    {
        return collect((array) config('laravelshopify.scopes'))->trim();
    }

    public static function for(string $url, string $suffix = '.myshopify.com') : ?static
    {
        return static::firstWhere('url', Str::finish($url, $suffix));
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory() : ShopifyShopFactory
    {
        return ShopifyShopFactory::new();
    }
}
