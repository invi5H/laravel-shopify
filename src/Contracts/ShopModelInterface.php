<?php

namespace Invi5h\LaravelShopify\Contracts;

use Illuminate\Support\Collection;

interface ShopModelInterface
{
    /**
     * @return Collection<int, string>
     */
    public static function defaultScopes() : Collection;

    public static function for(string $url, string $suffix = '.myshopify.com') : ?static;

    public function setup() : void;

    public function isAccessTokenValid() : bool;

    public function needsReauth() : bool;

    public function needsBilling() : bool;

    public function hasPendingBillingContract() : bool;

    public function allowedAccess() : bool;

    public function isDevShop() : bool;

    public function createBillingContract() : ?array;

    public function getbillingPageRedirectUrl() : string;

    /**
     * @return Collection<int, string>
     */
    public function requiredScopes() : Collection;
}
