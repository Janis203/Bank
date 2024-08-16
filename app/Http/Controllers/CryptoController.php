<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Currency;
use App\Models\Holding;
use App\Models\Transaction;
use App\Services\CoinMarketCapService;
use App\Services\ExchangeRateService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;


class CryptoController extends Controller
{
    protected CoinMarketCapService $coinMarketCapService;
    protected ExchangeRateService $exchangeRateService;

    public function __construct(CoinMarketCapService $coinMarketCapService, ExchangeRateService $exchangeRateService)
    {
        $this->coinMarketCapService = $coinMarketCapService;
        $this->exchangeRateService = $exchangeRateService;
    }

    public function index(): Factory|View|Application|RedirectResponse
    {
        $topCryptos = $this->coinMarketCapService->getTopCryptos();
        return view('crypto.index', compact('topCryptos'));
    }

    public function search(Request $request): JsonResponse
    {
        $query = strtoupper($request->input('search'));
        $cryptoData = $this->coinMarketCapService->getCryptoBySymbol($query);
        if ($cryptoData) {
            $results = [
                [
                    'rank' => $cryptoData['rank'] ?? 'N/A',
                    'name' => $cryptoData['name'],
                    'symbol' => $cryptoData['symbol'],
                    'current_price' => $cryptoData['price'],
                    'currency' => 'USD',
                    'last_updated' => $cryptoData['last_updated'] ?? 'N/A',
                ]
            ];
        } else {
            $results = [];
        }
        return response()->json(['results' => $results]);
    }

    /**
     * @throws \Exception
     */
    public function portfolio(): View|Factory|Application
    {
        $portfolio = Holding::with('currency')->where('user_id', auth()->id())->get();
        $accounts = Accounts::where('user_id', auth()->id())->get();
        $cryptoSymbols = $portfolio->pluck('crypto_id')->toArray();
        $currentPrices = [];
        foreach ($cryptoSymbols as $symbol) {
            $cryptoData = $this->coinMarketCapService->getCryptoBySymbol($symbol);
            if ($cryptoData) {
                $currentPrices[$symbol] = $cryptoData['price'];
            }
        }
        foreach ($portfolio as $item) {
            if ($item->currency !== 'USD') {
                $currentPrices[$item->crypto_id] = $this->exchangeRateService->convertCurrency($currentPrices[$item->crypto_id], 'USD', $item->currency);
            }
        }
        return view('crypto.portfolio', compact('portfolio', 'accounts', 'currentPrices'));
    }

    public function buyForm(Request $request): View|Factory|Application
    {
        $selectedCrypto = $request->query('crypto_symbol');
        $cryptos = Currency::where('type', 'crypto')->get();
        $accounts = Accounts::where('user_id', auth()->id())->get();
        return view('crypto.buy', compact('cryptos', 'accounts', 'selectedCrypto'));
    }

    /**
     * @throws \Exception
     */
    public function buy(Request $request): RedirectResponse
    {
        $request->validate([
            'crypto_symbol' => 'required|string',
            'amount' => 'required|numeric|min:0.0001',
            'account_id' => 'required|exists:accounts,id'
        ]);

        $cryptoData = $this->coinMarketCapService->getCryptoBySymbol($request->crypto_symbol);
        if (!$cryptoData) {
            return back()->withErrors(['crypto_symbol' => 'Cryptocurrency not found.']);
        }
        $cryptoPriceInUSD = $cryptoData['price'];

        $userAccount = Accounts::where('id', $request->account_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($userAccount->currency !== 'USD') {
            $cryptoPrice = $this->exchangeRateService->convertCurrency($cryptoPriceInUSD, 'USD', $userAccount->currency);
        } else {
            $cryptoPrice = $cryptoPriceInUSD;
        }
        $totalCost = $cryptoPrice * $request->amount;

        if ($userAccount->balance < $totalCost) {
            return back()->withErrors(['amount' => 'Insufficient funds.']);
        }

        $userAccount->decrement('balance', $totalCost);

        $existingHolding = Holding::where('user_id', auth()->id())
            ->where('crypto_id', $request->crypto_symbol)
            ->where('account_id', $userAccount->id)
            ->first();

        if ($existingHolding) {
            $existingTotalValue = $existingHolding->amount * $existingHolding->purchase_price;
            $newTotalValue = $existingTotalValue + $totalCost;
            $existingHolding->amount += $request->amount;
            $existingHolding->purchase_price = $newTotalValue / $existingHolding->amount;

            $existingHolding->save();
        } else {
            Holding::create([
                'user_id' => auth()->id(),
                'crypto_id' => $request->crypto_symbol,
                'amount' => $request->amount,
                'purchase_price' => $cryptoPrice,
                'currency' => $userAccount->currency,
                'account_name' => $userAccount->name,
                'account_id' => $userAccount->id,
            ]);
        }
        Transaction::create([
            'from_account_id' => $userAccount->id,
            'to_account_id' => null,
            'amount' => $totalCost,
            'type' => 'buy',
            'message' => "Bought {$request->amount} of {$request->crypto_symbol} at {$cryptoPrice} {$userAccount->currency} per unit",
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('crypto.portfolio')->with('success', 'Cryptocurrency bought successfully.');
    }

    public function sellForm($id): View|Factory|Application
    {
        $holding = Holding::findOrFail($id);
        return view('crypto.sell', compact('holding'));
    }

    public function sell(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.0001',
            'account_id' => 'required|exists:accounts,id'
        ]);
        $holding = Holding::findOrFail($id);
        $cryptoData = $this->coinMarketCapService->getCryptoBySymbol($holding->crypto_id);
        if (!$cryptoData) {
            return back()->withErrors(['crypto_id' => 'Cryptocurrency not found.']);
        }
        $cryptoPriceInUSD = $cryptoData['price'];
        $userAccount = Accounts::where('id', $request->account_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        if ($userAccount->currency !== 'USD') {
            $cryptoPrice = $this->exchangeRateService->convertCurrency($cryptoPriceInUSD, 'USD', $userAccount->currency);
        } else {
            $cryptoPrice = $cryptoPriceInUSD;
        }
        if ($holding->amount < $request->amount) {
            return back()->withErrors(['amount' => 'Insufficient crypto to sell.']);
        }
        $totalSaleValue = $request->amount * $cryptoPrice;
        $userAccount->increment('balance', $totalSaleValue);
        $holding->amount -= $request->amount;
        $holding->save();
        if ($holding->amount <= 0) {
            $holding->delete();
        }
        Transaction::create([
            'from_account_id' => $userAccount->id,
            'to_account_id' => null,
            'amount' => $totalSaleValue,
            'type' => 'sell',
            'message' => "Sold {$request->amount} of {$holding->crypto_id} at {$cryptoPrice} {$userAccount->currency} per unit",
            'user_id' => auth()->id(),
        ]);
        return redirect()->route('crypto.portfolio')->with('success', 'Cryptocurrency sold successfully.');
    }
}
