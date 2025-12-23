<?php

namespace App\Enums;

enum StockMarketEnum: string
{
    case BINANCE = 'binance';
    case JU = 'ju';
    case POLONIEX = 'poloniex';
    case BYBIT = 'bybit';
    case WHITEBIT = 'whitebit';
}
