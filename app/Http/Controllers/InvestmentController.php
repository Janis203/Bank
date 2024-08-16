<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Investment;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    public function investForm($accountId): View|Factory|Application
    {
        $account = Accounts::findOrFail($accountId);
        $investment = Investment::where('account_id', $accountId)
            ->where('user_id', auth()->id())
            ->first();
        return view('accounts.invest', compact('account', 'investment'));
    }

    public function invest(Request $request): RedirectResponse
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01'
        ]);

        $account = Accounts::findOrFail($request->account_id);
        $userId = auth()->id();

        $investment = Investment::where('account_id', $request->account_id)
            ->where('user_id', $userId)
            ->first();

        if ($account->balance < $request->amount) {
            return redirect()->back()->withErrors(['amount' => 'Insufficient funds in your account.']);
        }

        if ($investment) {
            $investment->amount += $request->amount;
        } else {
            $investment = new Investment([
                'account_id' => $request->account_id,
                'user_id' => $userId,
                'amount' => $request->amount,
                'invested_at' => now()
            ]);
        }
        $investment->save();

        $account->balance -= $request->amount;
        $account->save();

        return redirect()->route('accounts.index')->with('success', 'Investment updated successfully.');
    }

    public function withdraw(Request $request, $investmentId): RedirectResponse
    {
        $investment = Investment::findOrFail($investmentId);
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $investment->calculateCurrentValue()
        ]);

        $withdrawAmount = $request->amount;

        if ($investment->amount < $withdrawAmount) {
            return redirect()->back()->withErrors(['amount' => 'Insufficient funds in your investment.']);
        }

        $investment->amount -= $withdrawAmount;

        if ($investment->amount <= 0) {
            $investment->delete();
        } else {
            $investment->save();
        }

        $account = Accounts::findOrFail($investment->account_id);
        $account->balance += $withdrawAmount;
        $account->save();

        return redirect()->route('accounts.index', $investment->account_id)->with('success', 'Investment withdrawn successfully.');
    }
}
