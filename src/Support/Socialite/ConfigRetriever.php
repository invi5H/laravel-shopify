<?php

namespace Invi5h\LaravelShopify\Support\Socialite;

use SocialiteProviders\Manager\Helpers\ConfigRetriever as BaseConfigRetriever;

class ConfigRetriever extends BaseConfigRetriever
{
    protected function getConfigFromServicesArray($providerName)
    {
        if ('shopify' === $providerName) {
            $this->servicesArray = [
                    'subdomain' => config('laravelshopify.subdomain'),
                    'client_id' => config('laravelshopify.api_key'),
                    'client_secret' => config('laravelshopify.api_secret'),
                    'redirect' => '/callback',
            ];

            return $this->servicesArray;
        }

        // @codeCoverageIgnoreStart
        return parent::getConfigFromServicesArray($providerName);
        // @codeCoverageIgnoreEnd
    }
}
