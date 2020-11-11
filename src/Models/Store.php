<?php

namespace Invi5h\ShopifyHelper\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Invi5h\ShopifyHelper\Support\Facade\Graphql;
use Invi5h\ShopifyHelper\Support\Facade\Rest;
use Invi5h\ShopifyHelper\Support\Graphql\PendingRequest as GraphqlPendingRequest;
use Invi5h\ShopifyHelper\Support\Rest\PendingRequest as RestPendingRequest;

/**
 * @property int id
 * @property string domain
 * @property int shopify_id
 * @property string|null shopify_token
 * @property string|null shop_name
 * @property string|null shop_email
 * @property string|null admin_name
 * @property string|null admin_email
 * @property string|null contact_name
 * @property string|null contact_email
 * @property string|null primary_currency
 * @property string|null origin_country
 * @property string|null primary_language
 * @property string|null primary_location_id
 * @property string|null shopify_plan
 * @property string|null shopify_plan_display_name
 * @property Collection scope
 * @property Collection|null target_scope
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|static newModelQuery()
 * @method static Builder|static newQuery()
 * @method static Builder|static query()
 * @method static Builder|static whereCreatedAt($value)
 * @method static Builder|static whereId($value)
 * @method static Builder|static whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Store extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]|bool
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function getScopeAttribute(string $scope = null) : ?Collection
    {
        if ($scope) {
            return collect(array_map('trim', explode(',', $scope)));
        } else {
            return null;
        }
    }

    public function getTargetScopeAttribute(string $scope = null) : ?Collection
    {
        return $this->getScopeAttribute($scope);
    }

    /**
     * create a shopify rest api client
     *
     * @return RestPendingRequest
     */
    public function createRestClient()
    {
        return Rest::baseUrl($this->getBaseUrl())->withHeaders([
                'X-Shopify-Access-Token' => $this->shopify_token
        ]);
    }

    /**
     * create a shopify graphql api client
     *
     * @return GraphqlPendingRequest
     */
    public function createGraphqlClient()
    {
        return Graphql::withoutRedirecting()->baseUrl($this->getBaseUrl().'/graphql.json')->withHeaders([
                'X-Shopify-Access-Token' => $this->shopify_token
        ]);
    }

    /**
     * @return string
     */
    protected function getBaseUrl() : string
    {
        return 'https://'.$this->domain.'/admin/api/'.config('shopifyhelper.version');
    }
}
