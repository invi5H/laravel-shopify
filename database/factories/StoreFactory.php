<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Invi5h\ShopifyHelper\Models\Store;

class StoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Store::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
                'domain' => $this->faker->slug.'.myshopify.com',
                'shopify_id' => $this->faker->numberBetween(),
                'shopify_token' => null,
                'shop_name' => $this->faker->words($this->faker->numberBetween(1, 3), true),
                'shop_email' => $this->faker->email,
                'admin_name' => $this->faker->words($this->faker->numberBetween(1, 3), true),
                'admin_email' => $this->faker->email,
                'contact_name' => $this->faker->optional()->words($this->faker->numberBetween(1, 3), true),
                'contact_email' => $this->faker->optional()->email,
                'primary_currency' => $this->faker->currencyCode,
                'origin_country' => $this->faker->countryCode,
                'primary_language' => $this->faker->languageCode,
                'primary_location_id' => $this->faker->numberBetween(),
                'shopify_plan' => $this->faker->toLower($this->faker->word),
                'shopify_plan_display_name' => $this->faker->word,
                'scope' => 'read_products',
                'target_scope' => null
        ];
    }
}
