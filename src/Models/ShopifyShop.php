<?php

namespace Invi5h\LaravelShopify\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Invi5h\LaravelShopify\Contracts\Api\ResponseInterface;
use Invi5h\LaravelShopify\Contracts\ShopModelInterface;
use Invi5h\LaravelShopify\Database\Factories\ShopifyShopFactory;
use Invi5h\LaravelShopify\Events\ShopifyInstallEvent;
use Invi5h\LaravelShopify\Facades\Shopify;
use Invi5h\LaravelShopify\Support\ShopifyAppContext;
use Throwable;

/**
 * @property int $id
 * @property string $url
 * @property string $access_token
 * @property string $storefront_token
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
class ShopifyShop extends Model implements ShopModelInterface
{
    use HasFactory;

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
            'id',
            'url',
            'access_token',
            'name',
            'email',
            'domain',
            'scope',
            'plan',
            self::CREATED_AT,
            self::UPDATED_AT,
    ];

    protected $casts = [
            'dev' => 'bool',
            'plus' => 'bool',
            'scope' => 'array',
    ];

    public function setup() : void
    {
        ShopifyInstallEvent::dispatch($this);
    }

    public function isAccessTokenValid() : bool
    {
        try {
            return $this->getSelf()->successful();
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * @throws Throwable
     */
    public function reloadFromShopify() : void
    {
        $response = $this->getSelf();
        throw_unless($this->getSelf()->successful(), 'Access token is invalid.');
        $this->name = $response->json('data.shop.name');
        $this->email = $response->json('data.shop.email');
        $this->dev = (bool) $response->json('data.shop.plan.partnerDevelopment');
        $this->plus = (bool) $response->json('data.shop.plan.shopifyPlus');
        $this->save();
    }

    public function needsReauth() : bool
    {
        $scope = collect($this->scope);
        $required = $this->requiredScopes();
        foreach ($required as $value) {
            if (!$scope->contains($value) && !$scope->contains(Str::replaceFirst('read_', 'write_', $value))) {
                return true;
            }
        }

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
        return $this->dev;
    }

    public function isPlusShop() : bool
    {
        return $this->plus;
    }

    public function createBillingContract() : ?array
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
        return collect((array) config('laravelshopify.default_scopes'))->trim();
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

    protected function getSelf() : ResponseInterface
    {
        $query = <<<'QUERY'
          query {
            shop {
              name
              email
              plan {
                partnerDevelopment
                shopifyPlus
              }
            }
          }
        QUERY;

        return Shopify::setContext(new ShopifyAppContext($this))->graphql($query);
    }
}
