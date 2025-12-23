<?php

namespace App\Contracts;

use App\Data\Exchange\ExchangeData;
use App\Exceptions\ExchangeException;

interface ExchangeAdapterContract
{
    public function request(string $currency1, string $currency2): static;

    /**
     * @throws ExchangeException
     */
    public function get(): ExchangeData;
}
