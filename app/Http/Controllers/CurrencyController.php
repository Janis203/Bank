<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Currency;
use App\Services\CoinMarketCapService;
use App\Services\ExchangeRateService;

class CurrencyController extends Controller
{
    protected CoinMarketCapService $coinMarketCapService;
    protected ExchangeRateService $exchangeRateService;

    public function __construct(CoinMarketCapService $coinMarketCapService, ExchangeRateService $exchangeRateService)
    {
        $this->coinMarketCapService = $coinMarketCapService;
        $this->exchangeRateService = $exchangeRateService;
    }

    public function updateCurrencies(): JsonResponse
    {
        $cryptos = $this->coinMarketCapService->getTopCryptos();
        foreach ($cryptos as $crypto) {
            Currency::updateOrCreate(
                ['symbol' => $crypto['symbol'], 'type' => 'crypto'],
                ['name' => $crypto['name'], 'rate' => $crypto['quote']['USD']['price']]
            );
        }
        $rates = $this->exchangeRateService->getRates();

        foreach ($rates as $symbol => $rate) {
            Currency::updateOrCreate(
                ['symbol' => $symbol, 'type' => 'fiat'],
                ['name' => $symbol, 'rate' => $rate]
            );
        }
        return response()->json(['status' => 'Currencies updated successfully.']);
    }
}
