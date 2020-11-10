<?php

namespace Invi5h\ShopifyHelper\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;
use Invi5h\ShopifyHelper\Models\Store;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Manager\OAuth2\User;

class ShopifyController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Redirect the user to the Shopify Oauth flow
     *
     * @param  Request  $request
     *
     * @return Application|ResponseFactory|RedirectResponse|Response|Redirector
     */
    public function redirectToShopify(Request $request)
    {
        if ($request->input('shop')) {
            $store = Store::whereFirst('domain', $request->input('shop'));

            if (!($store instanceof Store)) {
                // this is a new store install request, redirect to shopify for authentication
                return Socialite::driver('shopify')->scopes($this->defaultAppScopes()->all())->redirect();
            }

            // this is an existing store
            $target_scopes = $store->target_scope ? collect(array_map('trim', explode(',', $store->target_scope))) : $this->defaultAppScopes();

            if ($this->verifyShopifyHmac($request->getQueryString()) && $request->input('shop')) {
                // the request is coming from shopify, so verify if we have all the required permissions

                if ($target_scopes->diff(array_map('trim', explode(',', $store->scope)))->isEmpty()) {
                    $this->resetStoreInSession($store);
                    return redirect()->route(config('shopifyhelper.redirect'));
                }
            }

            // re-authenticate with shopify with the new set of permissions
            return Socialite::driver('shopify')->scopes($target_scopes->all())->redirect();
        } elseif ($request->session()->has('store')) {
            //  the session is already authenticated and the store information is available
            return redirect()->route(config('shopifyhelper.redirect'));
        } else {
            return response('Missing shopify shop name', Response::HTTP_BAD_REQUEST);
        }
    }

    public function defaultAppScopes() : Collection
    {
        return collect(array_map('trim', explode(',', config('shopifyhelper.default_scope', 'read_products'))));
    }

    /**
     * Verifies the Hmac coming from shopify
     *
     * @param  string  $queryString
     *
     * @return bool
     */
    protected function verifyShopifyHmac(?string $queryString) : bool
    {
        if ($queryString) {
            preg_match('/hmac\=([^\&]+)\&/', $queryString, $matches);
            $query = str_replace($matches[0], '', $queryString);
            $signature = hash_hmac('sha256', $query, config('shopifyhelper.client_secret'));

            return $signature === $matches[1];
        } else {
            return false;
        }
    }

    /**
     * Set or remove the store data into the session
     *
     * @param  Store|null  $store
     */
    protected function resetStoreInSession(Store $store = null) : void
    {
        if ($store) {
            session()->put([
                    'store_id' => $store->id,
                    'store' => $store->domain,
                    'shopify_token' => $store->shopify_token,
                    'store_object' => $store
            ]);
        } else {
            session()->forget(['store_id', 'store', 'shopify_token', 'store_object']);
        }
    }

    /**
     * Obtain the Oauth information from Shopify
     *
     * @param  Request  $request
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function handleShopifyCallback(Request $request)
    {
        if ($this->verifyShopifyHmac($request->getQueryString()) && $request->input('shop')) {
            /** @var User $user */
            $user = Socialite::driver('shopify')->stateless()->user();

            $data = [
                    'domain' => $request->input('shop'),
                    'shopify_id' => $user->id,
                    'shopify_token' => $user->token,
                    'shop_name' => $user->name,
                    'shop_email' => $user->email,
                    'admin_name' => $user['shop_owner'],
                    'admin_email' => $user['customer_email'],
                    'contact_name' => $user->name,
                    'contact_email' => $user->email,
                    'primary_currency' => $user['currency'],
                    'origin_country' => $user['country'],
                    'primary_language' => $user['primary_locale'],
                    'primary_location_id' => $user['primary_location_id'],
                    'shopify_plan' => $user['plan_name'],
                    'shopify_plan_display_name' => $user['plan_display_name'],
                    'scope' => $user->accessTokenResponseBody['scope']
            ];
            /** @var Store $store */
            $store = Store::updateOrCreate(['domain' => $request->input('shop')], $data);

            $this->resetStoreInSession($store);
            return redirect()->route(config('shopifyhelper.redirect'));
        } else {
            $this->resetStoreInSession(null);
            return redirect()->route('shopify.login', $request->input('shop') ? ['shop' => $request->input('shop')] : []);
        }
    }
}
