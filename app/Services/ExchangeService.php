<?php

namespace App\Services;

use App\Contracts\ExchangeAdapterContract;
use App\Contracts\ExchangeServiceContract;
use App\Data\Exchange\ComparedPairsData;
use App\Data\Exchange\ExchangeData;
use App\Exceptions\ExchangeException;

class ExchangeService implements ExchangeServiceContract
{
    /**
     * @var ExchangeAdapterContract[]
     */
    private iterable $exchangeServices;

    public function __construct(
        iterable $exchangeServices,
    ) {
        $this->exchangeServices = $exchangeServices;
    }

    /**
     * @throws ExchangeException
     */
    public function comparePairs(string $currency1, string $currency2): ComparedPairsData
    {
        $values = [];

        foreach ($this->exchangeServices as $exchangeService) {
            $values[] = $exchangeService->request($currency1, $currency2)->get();
        }

        if (count($this->exchangeServices) != count($values)) {
            throw new ExchangeException('Not all stock markets have required currencies');
        }

        $collection = collect($values);

        // Min
        /** @var ExchangeData $buy */
        $buy = $collection->sortBy('price')->first();

        // Max
        /** @var ExchangeData $sell */
        $sell = $collection->sortByDesc('price')->first();

        if (
            $buy->price == $sell->price ||
            $buy->stockMarket == $sell->stockMarket
        ) {
            throw new ExchangeException('Price is same for all the markets');
        }

        // Diff
        $profitPercent = (($sell->price - $buy->price) / $buy->price) * 100;

        return ComparedPairsData::from([
            'buy' => $buy,
            'sell' => $sell,
            'profitPercent' => $profitPercent,
        ]);
    }
}
