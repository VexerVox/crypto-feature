<?php

namespace App\Contracts;

use App\Data\Exchange\ComparedPairsData;
use App\Exceptions\ExchangeException;

interface ExchangeServiceContract
{
    /**
     * @throws ExchangeException
     */
    public function comparePairs(string $currency1, string $currency2): ComparedPairsData;
}
