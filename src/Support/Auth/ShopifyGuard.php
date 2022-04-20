<?php

namespace Invi5h\LaravelShopify\Support\Auth;

use Carbon\CarbonInterval;
use DateInterval;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Invi5h\LaravelShopify\Models\ShopifyMerchant;
use Invi5h\LaravelShopify\Models\ShopifyShop;

class ShopifyGuard
{
    protected ?CarbonInterval $leeway;

    public function __construct(public string $apiKey, public Key $secretKey, DateInterval|string|null $leeway = '5 min')
    {
        $this->leeway = CarbonInterval::make($leeway ?? '5 min');
        if (!$this->leeway) {
            $this->leeway = CarbonInterval::make('5 min');
        }
    }

    public function __invoke(Request $request) : ?Authenticatable
    {
        $token = $request->bearerToken();
        if ($token) {
            /** @psalm-suppress PossiblyNullPropertyFetch */
            JWT::$leeway = (int) $this->leeway->totalSeconds;
            try {
                $decoded = JWT::decode($token, $this->secretKey);
            } catch (Exception) {
                // not a valid jwt token
                return null;
            }

            /*
            Sample decoded data-
            {
              "iss" => "https://test-hello-world-xyz.myshopify.com/admin"
              "dest" => "https://test-hello-world-xyz.myshopify.com"
              "aud" => "<the app's public secret key>"
              "sub" => "<id of the current logged-in user>"
              "exp" => <expiry timestamp>
              "nbf" => <not before timestamp>
              "iat" => <issued at timestamp>
            }
             */

            try {
                $url = parse_url($decoded->iss, PHP_URL_HOST);
                $class = config('laravelshopify.shop_model');

                /** @var ShopifyShop $shop */
                $shop = $class::for($url);
            } catch (ModelNotFoundException) {
                return null;
            }
            if (Str::contains($decoded->iss, $shop->url) && Str::contains($decoded->dest, $shop->url) && $this->apiKey === $decoded->aud) {
                return new ShopifyMerchant($shop, $decoded->sub);
            }
        }

        return null;
    }
}
