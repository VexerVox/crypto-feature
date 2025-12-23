<?php

namespace App\Services\Exchange;

use App\Contracts\ExchangeAdapterContract;
use App\Data\Exchange\ExchangeData;
use App\Enums\StockMarketEnum;
use App\Exceptions\ExchangeException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class BinanceExchangeService implements ExchangeAdapterContract
{
    private ?array $response;

    private string $pair;

    public function request(string $currency1, string $currency2): static
    {
        $this->pair = $currency1.'_'.$currency2;

        try {
            $this->response = Http::baseUrl(config('exchange.binance_base_url', 'https://api.binance.com'))
                ->get('/api/v3/ticker/price', [
                    'symbol' => $currency1.$currency2,
                ])->json();
        } catch (ConnectionException $e) {
            $this->response = null;
        }

        return $this;
    }

    /**
     * @throws ExchangeException
     */
    public function get(): ExchangeData
    {
        if (is_null($this->response)) {
            $stockMarket = StockMarketEnum::BINANCE->value;

            throw new ExchangeException("No currency available for $stockMarket or service unavailable");
        }

        return ExchangeData::from([
            'pair' => $this->pair,
            'price' => $this->response['price'],
            'stockMarket' => StockMarketEnum::BINANCE,
        ]);
    }
}
