<?php

namespace Invi5h\LaravelShopify\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Invi5h\LaravelShopify\Contracts\ShopModelInterface;

class ShopifyMerchant implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The primary key for the model.
     */
    protected string $primaryKey = 'id';

    public function __construct(public ShopModelInterface $shop, public string|int $id)
    {
    }

    /**
     * Get the primary key for the model.
     */
    public function getKeyName() : string
    {
        return $this->primaryKey;
    }

    /**
     * Get the class name for polymorphic relations.
     */
    public function getMorphClass() : string
    {
        $morphMap = Relation::morphMap();
        if (!empty($morphMap) && in_array(static::class, $morphMap, true)) {
            return (string) array_search(static::class, $morphMap, true);
        }

        return static::class;
    }
}
