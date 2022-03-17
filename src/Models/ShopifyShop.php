<?php

namespace Invi5h\LaravelShopify\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Invi5h\LaravelShopify\Database\Factories\ShopifyShopFactory;

/**
 * @property int $id
 * @property string $url
 * @property string $access_token
 * @property string $status
 * @property string $name
 * @property string $email
 * @property string $domain
 * @property bool $dev
 * @property bool $plus
 * @property array $scope
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @method static Builder|static newModelQuery()
 * @method static Builder|static newQuery()
 * @method static Builder|static query()
 * @mixin Eloquent
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
