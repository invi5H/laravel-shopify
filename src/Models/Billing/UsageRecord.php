<?php

namespace Invi5h\LaravelShopify\Models\Billing;

use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Invi5h\LaravelShopify\Contracts\AppUsageRecordInterface;

class UsageRecord extends ArrayObject implements AppUsageRecordInterface
{
    /**
     * @psalm-suppress NonInvariantPropertyType
     */
    public string $name;

    public float $amount;

    public string $currency;

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
}
