<?php

namespace App\Services;

use App\Models\Currency;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ExchangeRateService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('EXCHANGERATE_API_KEY');
    }

    /**
     * @throws Exception
     */
    public function getRates($baseCurrency = 'USD')
    {
        if ($baseCurrency !== 'USD') {
            throw new Exception("This method only supports USD as the base currency.");
        }

        $cacheKey = "exchange_rates_{$baseCurrency}";
        return Cache::remember($cacheKey, now()->addHours(24), function () use ($baseCurrency) {
            $response = Http::get("https://v6.exchangerate-api.com/v6/{$this->apiKey}/latest/{$baseCurrency}");

            if ($response->failed()) {
                throw new Exception('Failed to fetch exchange rates.');
            }

            $data = $response->json();
            $rates = $data['conversion_rates'];

            foreach ($rates as $currencyCode => $rate) {
                Currency::updateOrCreate(
                    ['symbol' => $currencyCode, 'type' => 'fiat'],
                    ['rate' => $rate, 'name' => $currencyCode]
                );
            }

            return $rates;
        });
    }

    public function getFiatCurrencies()
    {
        return Cache::remember('fiat_currencies', now()->addHours(24), function () {
            $response = Http::get("https://v6.exchangerate-api.com/v6/{$this->apiKey}/latest/USD");
            $data = $response->json();

            if ($response->failed()) {
                throw new Exception('Failed to fetch fiat currencies.');
            }

            $rates = $data['conversion_rates'];

            foreach ($rates as $currencyCode => $rate) {
                Currency::updateOrCreate(
                    ['symbol' => $currencyCode, 'type' => 'fiat'],
                    ['rate' => $rate, 'name' => $currencyCode]
                );
            }

            return $rates;
        });
    }

    /**
     * @throws Exception
     */
    public function convertCurrency($amount, $fromCurrency, $toCurrency)
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        $rates = $this->getRates();

        if (!isset($rates[$fromCurrency]) || !isset($rates[$toCurrency])) {
            throw new Exception("Conversion rate from {$fromCurrency} to {$toCurrency} not found.");
        }

        $amountInUSD = $amount / $rates[$fromCurrency];

        $conversionRate = $rates[$toCurrency];
        return $amountInUSD * $conversionRate;
    }
}
