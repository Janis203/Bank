<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Investment;
use App\Models\Transaction;
use App\Services\ExchangeRateService;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountsController extends Controller
{
    protected ExchangeRateService $exchangeRateService;

    public function __construct(ExchangeRateService $exchangeRateService)
    {
        $this->exchangeRateService = $exchangeRateService;
    }

    public function index(): View|Factory|Application
    {
        $accounts = Accounts::where('user_id', Auth::user()->id)->get();
        return view('accounts.index', compact('accounts'));
    }

    public function create(): View|Factory|Application
    {
        $fiatCurrencies = $this->exchangeRateService->getFiatCurrencies();
        return view('accounts.create', compact('fiatCurrencies'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => 'required|in:' . implode(',', Accounts::TYPES),
            'currency' => 'required|string|max:3',
            'name' => 'required|string|max:55',
            'balance' => 'required|numeric|min:0'
        ]);

        Accounts::create([
            'type' => $request->input('type'),
            'currency' => $request->input('currency'),
            'name' => $request->input('name'),
            'balance' => $request->input('balance'),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('accounts.index')->with('success', 'Account created successfully');
    }

    public function transferForm($account_id): View|Factory|Application
    {
        $account = Accounts::findOrFail($account_id);
        $accounts = Accounts::where('user_id', Auth::id())->where('id', '!=', $account_id)->get();
        return view('accounts.transfer', compact('account', 'accounts'));
    }

    /**
     * @throws \Exception
     */
    public function transfer(Request $request): RedirectResponse
    {
        $request->validate([
            'from_account_id' => 'required|exists:accounts,id',
            'to_account_id' => 'required|string|max:34',
            'amount' => 'required|numeric|min:0.01',
            'message' => 'nullable|string|max:255'
        ]);

        $fromAccount = Accounts::find($request->from_account_id);
        $toAccount = Accounts::where('iban', $request->to_account_id)->first();
        if (!$toAccount) {
            return back()->withErrors(['to_account_id' => 'To account not found.']);
        }

        if ($fromAccount->balance < $request->amount) {
            return back()->withErrors(['amount' => 'Insufficient balance.']);
        }

        $amountToTransfer = $request->amount;
        if ($fromAccount->currency !== $toAccount->currency) {
            $amountToTransfer = $this->exchangeRateService->convertCurrency($request->amount, $fromAccount->currency, $toAccount->currency);
        }

        Transaction::create([
            'from_account_id' => $fromAccount->id,
            'to_account_id' => $toAccount->id,
            'amount' => $amountToTransfer,
            'message' => $request->message ?? '-',
            'user_id' => auth()->id(),
            'type' => Transaction::TYPE_TRANSFER
        ]);

        $fromAccount->decrement('balance', $request->amount);
        $toAccount->increment('balance', $amountToTransfer);

        return redirect()->route('accounts.index')->with('success', 'Money transferred successfully.');
    }


    public function edit(Accounts $account): View|Factory|Application
    {
        $fiatCurrencies = $this->exchangeRateService->getFiatCurrencies();
        return view('accounts.edit', compact('account', 'fiatCurrencies'));
    }

    public function update(Request $request, Accounts $account): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:55',
            'balance' => 'required|numeric|min:0'
        ]);

        $account->update([
            'name' => $request->input('name'),
            'balance' => $request->input('balance')
        ]);

        return redirect()->route('accounts.index')->with('success', 'Account updated successfully.');
    }

    public function showTransactions(Accounts $account): View|Factory|Application
    {
        $transactions = Transaction::where('from_account_id', $account->id)
            ->orWhere('to_account_id', $account->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('accounts.transactions', compact('transactions', 'account'));
    }
}
