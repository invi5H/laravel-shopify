<?php

namespace Invi5h\LaravelShopify\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Invi5h\LaravelShopify\Models\ShopifyShop;

/**
 * @extends Factory<ShopifyShop>
 */
class ShopifyShopFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     * @psalm-suppress NonInvariantDocblockPropertyType
     */
    protected $model = ShopifyShop::class;

    /**
     * Define the model's default state.
     *
     * @return array<empty, empty>
     */
    public function definition() : array
    {
        return [];
    }
}
