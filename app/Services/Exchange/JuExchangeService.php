<?php

namespace App\Services\Exchange;

use App\Contracts\ExchangeAdapterContract;
use App\Data\Exchange\ExchangeData;
use App\Enums\StockMarketEnum;
use App\Exceptions\ExchangeException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class JuExchangeService implements ExchangeAdapterContract
{
    private ?array $response;

    private string $pair;

    public function request(string $currency1, string $currency2): static
    {
        $this->pair = $currency1.'_'.$currency2;

        try {
            $this->response = Http::baseUrl('https://api.jucoin.com')
                ->get('/v1/spot/public/ticker', [
                    'symbol' => $currency1.'_'.$currency2,
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
            || empty($this->response['data'][0])
            || empty($this->response['data'][0]['s'])
            || empty($this->response['data'][0]['c'])
        ) {
            $stockMarket = StockMarketEnum::JU->value;

            throw new ExchangeException("No currency available for $stockMarket or service unavailable");
        }

        return ExchangeData::from([
            'pair' => $this->pair,
            'price' => $this->response['data'][0]['c'],
            'stockMarket' => StockMarketEnum::JU,
        ]);
    }
}
