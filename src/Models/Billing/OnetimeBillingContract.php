<?php

namespace Invi5h\LaravelShopify\Models\Billing;

use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Invi5h\LaravelShopify\Contracts\OnetimeBillingContractInterface;
use JetBrains\PhpStorm\Pure;

class OnetimeBillingContract extends ArrayObject implements OnetimeBillingContractInterface
{
    /**
     * @psalm-suppress NonInvariantPropertyType
     */
    public string $name;

    public float $amount;

    public string $currency;

    public string $url;

    public bool $testMode;

    public function getName() : string
    {
        return $this->name;
    }

    public function setName(string $name) : self
    {
        $this->name = $name;

        return $this;
    }

    public function getAmount() : float
    {
        return $this->amount;
    }

    public function setAmount(float $amount) : self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCurrency() : string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency) : self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getUrl() : string
    {
        return $this->url;
    }

    public function setUrl(string $url) : self
    {
        $this->url = $url;

        return $this;
    }

    #[Pure]
    public function isTestMode() : bool
    {
        return $this->getTestMode();
    }

    public function getTestMode() : bool
    {
        return $this->testMode;
    }

    public function setTestMode(bool $testMode = true) : self
    {
        $this->testMode = $testMode;

        return $this;
    }

    public function inTestMode(bool $testMode = true) : self
    {
        return $this->setTestMode($testMode);
    }
}
