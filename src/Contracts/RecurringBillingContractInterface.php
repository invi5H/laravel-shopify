<?php

namespace Invi5h\LaravelShopify\Contracts;

interface RecurringBillingContractInterface extends OnetimeBillingContractInterface
{
    public function getInterval() : string;

    public function setInterval(string $interval) : self;

    public function getTrialDays() : int;

    public function setTrialDays(int $trialDays) : self;

    public function getUsageCap() : float;

    public function setUsageCap(float $usageCap) : self;
}
