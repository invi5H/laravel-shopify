<?php

namespace Invi5h\LaravelShopify\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
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
     * @return array<string, string>
     * @psalm-return array{url: string, access_token: string, storefront_token: string}
     */
    public function definition() : array
    {
        return [
                'url' => Str::slug((string) $this->faker->words($this->faker->biasedNumberBetween(1, 3), true)).'.myshopify.com',
                'access_token' => Str::random(),
                'storefront_token' => Str::random(),
        ];
    }
}
