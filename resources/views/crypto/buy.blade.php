<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buy Cryptocurrency') }}
        </h2>
    </x-slot>

    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold mb-6 text-white">Buy Cryptocurrency</h1>

        <form action="{{ route('crypto.buy') }}" method="POST" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            @csrf

            <div class="mb-4">
                <label for="account_id" class="block text-white text-sm font-bold mb-2">{{ __('Select Account') }}</label>
                <select name="account_id" id="account_id" class="w-full p-2 rounded border-gray-300" required>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->currency }})</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="crypto_symbol" class="block text-white text-sm font-bold mb-2">{{ __('Cryptocurrency') }}</label>
                <select name="crypto_symbol" id="crypto_symbol" class="w-full p-2 rounded border-gray-300" required>
                    @foreach($cryptos as $crypto)
                        <option value="{{ $crypto->symbol }}">{{ $crypto->symbol }} ({{ $crypto->name }}) ({{ $crypto->currency }}) {{ $crypto->rate }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="amount" class="block text-white text-sm font-bold mb-2">{{ __('Amount to Spend (in Account Currency)') }}</label>
                <input type="number" name="amount" id="amount" class="w-full p-2 rounded border-gray-300" step="0.0001" required>
            </div>
            <x-primary-button>{{ __('Buy') }}</x-primary-button>
        </form>
    </div>
    @if($errors->any())
        <div class="mb-4 bg-red-100 text-red-600 p-4 rounded">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
</x-app-layout>
