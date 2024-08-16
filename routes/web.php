<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\CryptoController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/accounts', [AccountsController::class, 'index'])->name('accounts.index');
    Route::get('/accounts/create', [AccountsController::class, 'create'])->name('accounts.create');
    Route::post('/accounts', [AccountsController::class, 'store'])->name('accounts.store');
    Route::get('/accounts/{account}/edit', [AccountsController::class, 'edit'])->name('accounts.edit');
    Route::patch('/accounts/{account}', [AccountsController::class, 'update'])->name('accounts.update');
    Route::get('/accounts/transfer/{account_id}', [AccountsController::class, 'transferForm'])->name('accounts.transferForm');
    Route::post('/accounts/transfer', [AccountsController::class, 'transfer'])->name('accounts.transfer');
    Route::get('/accounts/{account}/transactions', [AccountsController::class, 'showTransactions'])->name('accounts.transactions');

    Route::get('accounts/{account}/invest', [InvestmentController::class, 'investForm'])->name('accounts.invest');
    Route::post('accounts/invest', [InvestmentController::class, 'invest'])->name('accounts.invest.post');
    Route::put('investments/{investment}/withdraw', [InvestmentController::class, 'withdraw'])->name('investments.withdraw');

    Route::get('/crypto/index', [CryptoController::class, 'index'])->name('crypto.index');
    Route::get('/crypto/search', [CryptoController::class, 'search'])->name('crypto.search');
    Route::get('/crypto/portfolio', [CryptoController::class, 'portfolio'])->name('crypto.portfolio');
    Route::get('/crypto/buy', [CryptoController::class, 'buyForm'])->name('crypto.buyForm');
    Route::post('/crypto/buy', [CryptoController::class, 'buy'])->name('crypto.buy');
    Route::get('/crypto/sell/{id}', [CryptoController::class, 'sellForm'])->name('crypto.sellForm');
    Route::post('/crypto/sell/{id}', [CryptoController::class, 'sell'])->name('crypto.sell');

    Route::get('/currencies/update', [CurrencyController::class, 'updateCurrencies'])->name('currencies.update');

});

require __DIR__ . '/auth.php';
