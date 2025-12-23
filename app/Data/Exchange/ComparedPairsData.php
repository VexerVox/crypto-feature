<?php

namespace App\Data\Exchange;

use Spatie\LaravelData\Data;

class ComparedPairsData extends Data
{
    public function __construct(
        public ExchangeData $buy,
        public ExchangeData $sell,
        public float|int $profitPercent,
    ) {}
}
