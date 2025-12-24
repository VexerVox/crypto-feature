## Setup:

```shell
git clone git@github.com:VexerVox/crypto-feature.git;
cd crypto-feature;
cp .env.example .env;
docker compose up -d;
```

### Run command:
```shell
docker exec -it cryptofeature-php bash;
php artisan crypto:compare BTC USDT;
```

## Task:

Develop a mechanism for analyzing prices on the following cryptocurrency exchanges:

- binance.com
- ju.com
- poloniex.com
- bybit.com
- whitebit.com

Requirements:
- Find the required API endpoints to obtain current prices for all available currency pairs on each of the exchanges listed above.
- Currency pairs must be the same across all the listed exchanges (if a particular currency pair is not available on one of the exchanges, that pair should be skipped and not analyzed).
- Develop a console command to retrieve the lowest and highest price for a user-selected currency pair and display which exchange offers that price (for example, for BTC/USDT, show the price and the exchange name).

Additionally:
- Develop a console command that generates a list of currency pairs with a profit percentage. The system should find the lowest price of a currency pair on exchange A, the highest price of the same pair on exchange B, and calculate the profit percentage when buying on exchange A and selling on exchange B.
