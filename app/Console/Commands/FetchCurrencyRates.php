<?php

namespace App\Console\Commands;

use App\Services\CurrencyService;
use Illuminate\Console\Command;
use App\Models\Currency;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class FetchCurrencyRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch-currency-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the latest currency rates';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $fiatRates = Http::get('https://api.exchangerate-api.com/v4/latest/USD')->json();
        $cryptoRates = Http::get('https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest', [
            'CMC_PRO_API_KEY' => env('COINMARKETCAP_API_KEY'),
        ])->json();
        foreach ($fiatRates['rates'] as $symbol => $rate) {
            Currency::updateOrCreate(
                ['symbol' => $symbol, 'type' => Currency::TYPE_FIAT],
                ['name' => $symbol, 'price' => $rate]
            );
        }
        foreach ($cryptoRates['data'] as $crypto) {
            Currency::updateOrCreate(
                ['symbol' => $crypto['symbol'], 'type' => Currency::TYPE_CRYPTO],
                ['name' => $crypto['name'], 'price' => $crypto['quote']['USD']['price']]
            );
        }

        Cache::forget('top_cryptos');
    }
}
