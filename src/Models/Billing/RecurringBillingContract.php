<?php

namespace Invi5h\LaravelShopify\Models\Billing;

use Invi5h\LaravelShopify\Contracts\RecurringBillingContractInterface;

class RecurringBillingContract extends OnetimeBillingContract implements RecurringBillingContractInterface
{
    public string $interval;

    public int $trialDays;

    public float $usageCap;

    public function getInterval() : string
    {
        return $this->interval;
    }

    public function setInterval(string $interval) : self
    {
        $this->interval = $interval;

        return $this;
    }

    public function getTrialDays() : int
    {
        return $this->trialDays;
    }

    public function setTrialDays(int $trialDays) : self
    {
        $this->trialDays = $trialDays;

        return $this;
    }

    public function getUsageCap() : float
    {
        return $this->usageCap;
    }

    public function setUsageCap(float $usageCap) : self
    {
        $this->usageCap = $usageCap;

        return $this;
    }
}
