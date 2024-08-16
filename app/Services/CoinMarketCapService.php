<?php

namespace App\Services;

use App\Models\Currency;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CoinMarketCapService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('COINMARKETCAP_API_KEY');
    }

    public function getTopCryptos(int $limit = 10, string $currency = 'USD')
    {
        return Cache::remember('top_cryptos', now()->addMinutes(30), function () use ($limit, $currency) {
            $response = Http::withHeaders([
                'X-CMC_PRO_API_KEY' => $this->apiKey,
            ])->get('https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest', [
                'start' => 1,
                'limit' => $limit,
                'convert' => $currency,
            ]);
            $cryptos = $response->json()['data'];
            foreach ($cryptos as &$cryptoData) {
                $symbol = $cryptoData['symbol'];
                $price = $cryptoData['quote'][$currency]['price'];
                $rank = $cryptoData['cmc_rank'] ?? 'N/A';
                $lastUpdated = $cryptoData['last_updated'] ?? 'N/A';
                Currency::updateOrCreate(
                    ['symbol' => $symbol, 'type' => Currency::TYPE_CRYPTO],
                    [
                        'name' => $cryptoData['name'],
                        'rate' => $price,
                        'currency' => $currency,
                        'rank' => $rank,
                        'last_updated' => $lastUpdated
                    ]
                );
                $cryptoData['current_price'] = $price;
                $cryptoData['currency'] = $currency;
                $cryptoData['rank'] = $rank;
                $cryptoData['last_updated'] = $lastUpdated;
            }
            return $cryptos;
        });
    }

    public function getCryptoBySymbol(string $symbol, string $currency = 'USD')
    {
        $symbol = strtoupper($symbol);
        $currency = strtoupper($currency);
        $cacheKey = "crypto_by_symbol_{$symbol}_{$currency}";

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($symbol, $currency) {
            $response = Http::withHeaders([
                'X-CMC_PRO_API_KEY' => $this->apiKey,
            ])->get('https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest', [
                'symbol' => $symbol,
                'convert' => $currency,
            ]);
            if ($response->successful() && isset($response->json()['data'][$symbol])) {
                $cryptoData = $response->json()['data'][$symbol];
                $price = $cryptoData['quote'][$currency]['price'];
                $rank = $cryptoData['cmc_rank'] ?? 'N/A';
                $lastUpdated = $cryptoData['last_updated'] ?? 'N/A';
                Currency::updateOrCreate(
                    ['symbol' => $cryptoData['symbol'], 'type' => Currency::TYPE_CRYPTO],
                    [
                        'name' => $cryptoData['name'],
                        'rate' => $price,
                        'currency' => $currency,
                        'rank' => $rank,
                        'last_updated' => $lastUpdated,
                    ]
                );
                return [
                    'id' => $cryptoData['id'],
                    'name' => $cryptoData['name'],
                    'symbol' => $cryptoData['symbol'],
                    'price' => $price,
                    'rank' => $rank,
                    'last_updated' => $lastUpdated,
                ];
            }
            return null;
        });
    }

}
