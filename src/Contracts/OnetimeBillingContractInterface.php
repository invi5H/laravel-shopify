<?php

namespace Invi5h\LaravelShopify\Contracts;

use Illuminate\Contracts\Support\Arrayable;

interface OnetimeBillingContractInterface extends Arrayable
{
    public function getName() : string;

    public function setName(string $name) : self;

    public function getAmount() : float;

    public function setAmount(float $amount) : self;

    public function getCurrency() : string;

    public function setCurrency(string $currency) : self;

    public function getUrl() : string;

    public function setUrl(string $url) : self;

    public function isTestMode() : bool;

    public function setTestMode(bool $testMode = true) : self;

    public function inTestMode(bool $testMode = true) : self;
}
