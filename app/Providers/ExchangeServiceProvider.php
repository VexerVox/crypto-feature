<?php

namespace App\Providers;

use App\Contracts\ExchangeAdapterContract;
use App\Contracts\ExchangeServiceContract;
use App\Services\Exchange\BinanceExchangeService;
use App\Services\Exchange\BybitExchangeService;
use App\Services\Exchange\JuExchangeService;
use App\Services\Exchange\PoloniexExchangeService;
use App\Services\Exchange\WhitebitExchangeService;
use App\Services\ExchangeService;
use Illuminate\Support\ServiceProvider;

class ExchangeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this
            ->app
            ->tag([
                BinanceExchangeService::class,
                BybitExchangeService::class,
                JuExchangeService::class,
                PoloniexExchangeService::class,
                WhitebitExchangeService::class,
            ], ExchangeAdapterContract::class);

        $this->app->bind(ExchangeServiceContract::class, function (): ExchangeService {
            return new ExchangeService(
                $this->app->tagged(ExchangeAdapterContract::class)
            );
        });
    }

    public function boot(): void {}
}
