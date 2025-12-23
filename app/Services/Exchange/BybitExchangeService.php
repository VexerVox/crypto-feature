<?php

namespace App\Services\Exchange;

use App\Contracts\ExchangeAdapterContract;
use App\Data\Exchange\ExchangeData;
use App\Enums\StockMarketEnum;
use App\Exceptions\ExchangeException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class BybitExchangeService implements ExchangeAdapterContract
{
    private ?array $response;

    private string $pair;

    public function request(string $currency1, string $currency2): static
    {
        $this->pair = $currency1.'_'.$currency2;

        try {
            $this->response = Http::baseUrl('https://api.bybit.com')
                ->get('/v5/market/tickers', [
                    'category' => 'spot',
                    'symbol' => $currency1.$currency2,
                ])->json();
        } catch (ConnectionException $e) {
            $this->response = null;
        }

        return $this;
    }

    public function get(): ExchangeData
    {
        if (
            is_null($this->response)
            || empty($this->response['result'])
            || empty($this->response['result']['list'])
            || empty($this->response['result']['list'][0])
            || empty($this->response['result']['list'][0]['symbol'])
            || empty($this->response['result']['list'][0]['lastPrice'])
        ) {
            $stockMarket = StockMarketEnum::BYBIT->value;

            throw new ExchangeException("No currency available for $stockMarket or service unavailable");
        }

        return ExchangeData::from([
            'pair' => $this->pair,
            'price' => $this->response['result']['list'][0]['lastPrice'],
            'stockMarket' => StockMarketEnum::BYBIT,
        ]);
    }
}
