<?php

namespace App\Console\Commands;

use App\Contracts\ExchangeServiceContract;
use App\Exceptions\ExchangeException;
use Illuminate\Console\Command;

class CryptoCompareCommand extends Command
{
    protected $signature = 'crypto:compare {currency1} {currency2}';

    protected $description = 'Get lowest and highest price for given pair';

    private ExchangeServiceContract $exchangeService;

    public function __construct(ExchangeServiceContract $exchangeService)
    {
        parent::__construct();

        $this->exchangeService = $exchangeService;
    }

    public function handle(): void
    {
        $currency1 = $this->argument('currency1');
        $currency2 = $this->argument('currency2');

        try {
            $comparedPairs = $this->exchangeService->comparePairs($currency1, $currency2);
        } catch (ExchangeException $e) {
            $this->error($e->getMessage());

            return;
        }

        $this->line('-------------------------------------------');
        $this->info('Info for pair '.$currency1.' '.$currency2);
        $this->line('');
        $this->info('Min price: '.$comparedPairs->buy->price);
        $this->info('Stock market: '.$comparedPairs->buy->stockMarket->value);
        $this->line('');
        $this->info('Max price: '.$comparedPairs->sell->price);
        $this->info('Stock market: '.$comparedPairs->sell->stockMarket->value);
        $this->line('');
        $this->info('Percent of profit: '.$comparedPairs->profitPercent);
        $this->line('-------------------------------------------');
    }
}
