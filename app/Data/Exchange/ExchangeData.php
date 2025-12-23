<?php

namespace App\Data\Exchange;

use App\Enums\StockMarketEnum;
use Spatie\LaravelData\Data;

class ExchangeData extends Data
{
    public function __construct(
        public string $pair,
        public int|float $price,
        public StockMarketEnum $stockMarket,
    ) {}
}
