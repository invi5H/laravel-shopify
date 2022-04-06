<?php

namespace Invi5h\LaravelShopify\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Invi5h\LaravelShopify\Contracts\ShopModelInterface;
use Invi5h\LaravelShopify\Models\ShopifyShop;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Shopify\Provider;

class LaravelShopifyController extends Controller
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    private string $shopModel;

    public function __construct()
    {
        $this->shopModel = (config('laravelshopify.shop_model') ?? ShopifyShop::class);
    }

    /**
     * Homepage to the shopify app.
     * This will check if the app is installed or not and act accordingly.
     * It may redirect the user to the actual homepage for the app, or redirect to the shopify oauth flow.
     *
     * @codeCoverageIgnore
     */
    public function home(Request $request) : RedirectResponse|Response
    {
        $shop = (string) $request->input('shop');
        if ($shop) {
            Log::debug('App Launch for Shop- '.$shop);

            /** @var ?ShopModelInterface $shopObject */
            $shopObject = $this->shopModel::for($shop);

            if (null === $shopObject) {
                Log::debug('New Shop Install- '.$shop);

                return $this->getOauthResponse($this->shopModel::defaultScopes());
            }

            if (!$shopObject->isAccessTokenValid()) {
                Log::debug('Invalid Access Token detected- '.$shop);

                return $this->getOauthResponse($this->shopModel::defaultScopes());
            }

            if ($shopObject->needsReauth()) {
                Log::debug('Shop needs reauth- '.$shop);

                return $this->getOauthResponse($shopObject->requiredScopes());
            }

            if ($shopObject->needsBilling()) {
                Log::debug('Making billing contract- '.$shop);

                $shopObject->createBillingContract();
            }

            if ($shopObject->hasPendingBillingContract()) {
                Log::debug('Pending billing contract found- '.$shop);

                return $this->billingPageRedirectResponse($shopObject);
            }

            if ($shopObject->allowedAccess()) {
                Log::debug('Display main app view- '.$shop);

                return $this->redirectToAppView();
            }

            Log::debug('Access Denied- '.$shop);
        } else {
            Log::error('No Shop Input');
        }

        $this->renderAccessDeniedView();
    }

    /**
     * Callback for the shopify oauth flow.
     *
     * @codeCoverageIgnore
     */
    public function callback(Request $request) : RedirectResponse|Response
    {
        $shop = (string) $request->input('shop');
        if ($shop && $this->verifyShopifyHmac($request->getQueryString())) {
            Log::debug('Shopify callback for Shop- '.$shop);
            $user = $this->getOauthDriver()->user();

            $data = [
                    'id' => $user->id,
                    'url' => $shop,
                    'access_token' => $user->token,
                    'name' => $user->name,
                    'email' => $user->email,
                    'domain' => $user['domain'],
                    'scope' => $user->accessTokenResponseBody['scope'],
                    'dev' => Str::contains($user['plan_name'], 'dev'),
                    'plus' => Str::contains($user['plan_name'], 'plus'),
                    'created_at' => Carbon::parse($user['created_at']),
                    'updated_at' => Carbon::parse($user['updated_at']),
            ];

            /** @var ShopModelInterface $shopObject */
            $shopObject = $this->shopModel::updateOrCreate(['url' => $shop], $data);
            $shopObject->setup();

            if ($shopObject->needsBilling()) {
                Log::debug('Making billing contract- '.$shop);

                $shopObject->createBillingContract();
            }

            if ($shopObject->hasPendingBillingContract()) {
                Log::debug('Pending billing contract found- '.$shop);

                return $this->billingPageRedirectResponse($shopObject);
            }

            return redirect()->away($this->getAppUrl($shopObject));
        }

        return redirect()->route('laravelshopify.home', ['shop' => $shop]);
    }

    /**
     * @psalm-suppress InvalidReturnStatement
     * @psalm-suppress InvalidReturnType
     */
    protected function billingPageRedirectResponse(ShopModelInterface $shop) : Response
    {
        $url = $shop->getbillingPageRedirectUrl();

        return response("<script>window.top.location.href='{$url}'</script>");
    }

    /**
     * Verifies the Hmac coming from shopify.
     *
     * @codeCoverageIgnore
     */
    protected function verifyShopifyHmac(?string $queryString) : bool
    {
        Log::debug('Verifying Shopify Hmac');
        if ($queryString) {
            preg_match('/hmac\=([^\&]+)\&/', $queryString, $matches);
            $query = str_replace($matches[0], '', $queryString);
            $signature = hash_hmac('sha256', $query, (string) config('laravelshopify.api_secret'));

            return $signature === $matches[1];
        }

        return false;
    }

    /**
     * @psalm-suppress UndefinedInterfaceMethod
     */
    public function getOauthDriver() : Provider
    {
        return Socialite::driver('shopify')->stateless();
    }

    /**
     * @param  array<int,string>|Collection|string  $scopes
     */
    protected function getOauthResponse(string|array|Collection $scopes) : RedirectResponse
    {
        if (is_string($scopes)) {
            $scopes = explode(',', $scopes);
        }

        /**
         * @var array $scopes
         * @psalm-suppress InvalidArgument
         */
        $scopes = collect($scopes)->values()->trim()->all();

        return $this->getOauthDriver()->scopes($scopes)->redirect();
    }

    protected function redirectToAppView() : RedirectResponse
    {
        return redirect()->home();
    }

    protected function renderAccessDeniedView() : never
    {
        abort(Response::HTTP_BAD_REQUEST, 'Invalid Request');
    }

    /**
     * @psalm-suppress NoInterfaceProperties
     */
    protected function getAppUrl(ShopModelInterface $shopObject) : string
    {
        return 'https://'.$shopObject->url.'/admin/apps/'.config('laravelshopify.url');
    }
}
