<?php

namespace App\Services\Exchange;

use App\Contracts\ExchangeAdapterContract;
use App\Data\Exchange\ExchangeData;
use App\Enums\StockMarketEnum;
use App\Exceptions\ExchangeException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class PoloniexExchangeService implements ExchangeAdapterContract
{
    private ?array $response;

    private string $pair;

    public function request(string $currency1, string $currency2): static
    {
        $this->pair = $currency1.'_'.$currency2;

        try {
            $this->response = Http::baseUrl(config('exchange.poloniex_base_url', 'https://futures-api.poloniex.com'))
                ->get('/api/v2/ticker', [
                    'symbol' => $currency1.$currency2.'PERP',
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
            || empty($this->response['data'])
            || empty($this->response['data']['symbol'])
            || empty($this->response['data']['price'])
        ) {
            $stockMarket = StockMarketEnum::POLONIEX->value;

            throw new ExchangeException("No currency available for $stockMarket or service unavailable");
        }

        return ExchangeData::from([
            'pair' => $this->pair,
            'price' => $this->response['data']['price'],
            'stockMarket' => StockMarketEnum::POLONIEX,
        ]);
    }
}
