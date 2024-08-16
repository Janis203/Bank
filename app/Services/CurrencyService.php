<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class CurrencyService
{
    protected Client $client;
    protected string $exchangeRateApiKey;
    protected string $cryptoApiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->exchangeRateApiKey = env('EXCHANGERATE_API_KEY');
        $this->cryptoApiKey = env('COINMARKETCAP_API_KEY');
    }

    public function getExchangeRate($from, $to)
    {
        $cacheKey = "exchange_rate_{$from}_{$to}";
        return Cache::remember($cacheKey, 60, function () use ($from, $to) {
            $response = $this->client->get("https://api.exchangerate-api.com/v4/latest/{$from}");
            $rates = json_decode($response->getBody(), true)['rates'];
            return $rates[$to] ?? null;
        });
    }

    public function getCryptoPrices()
    {
        $cacheKey = 'crypto_prices';
        return Cache::remember($cacheKey, 60, function () {
            $response = $this->client->get("https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest", [
                'headers' => [
                    'X-CMC_PRO_API_KEY' => $this->cryptoApiKey,
                ],
            ]);
            return json_decode($response->getBody(), true)['data'];
        });
    }

}
