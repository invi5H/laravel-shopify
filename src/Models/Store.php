<?php

namespace Invi5h\ShopifyHelper\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string domain
 * @property int shopify_id
 * @property string shopify_token
 * @property string shop_name
 * @property string shop_email
 * @property string admin_name
 * @property string admin_email
 * @property string contact_name
 * @property string contact_email
 * @property string primary_currency
 * @property string origin_country
 * @property string primary_language
 * @property string primary_location_id
 * @property string shopify_plan
 * @property string shopify_plan_display_name
 * @property string scope
 * @property string target_scope
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

}
