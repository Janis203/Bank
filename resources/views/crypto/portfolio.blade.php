<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crypto Portfolio') }}
        </h2>
    </x-slot>

    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold mb-6 text-white">My Crypto Portfolio</h1>

        <div class="mb-4">
            <x-primary-button>
                <a href="{{ route('crypto.buyForm') }}" class="text-blue-500 hover:underline">Buy Cryptocurrency</a>
            </x-primary-button>
        </div>

        <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <thead>
            <tr>
                <th class="py-2 px-4 border-b text-white">{{ __('Crypto') }}</th>
                <th class="py-2 px-4 border-b text-white">{{ __('Account Name') }}</th>
                <th class="py-2 px-4 border-b text-white">{{ __('Account Currency') }}</th>
                <th class="py-2 px-4 border-b text-white">{{ __('Amount') }}</th>
                <th class="py-2 px-4 border-b text-white">{{ __('Purchase Price') }}</th>
                <th class="py-2 px-4 border-b text-white">{{ __('Current Price') }}</th>
                <th class="py-2 px-4 border-b text-white">{{ __('Profit/Loss') }}</th>
                <th class="py-2 px-4 border-b text-white">{{ __('Action') }}</th>
            </tr>
            </thead>
            <tbody>@foreach($portfolio as $item)
                @php
                    $currentPrice = $currentPrices[$item->crypto_id] ?? 0;
                    $totalCost = $item->amount * $item->purchase_price;
                    $currentValue = $item->amount * $currentPrice;
                    $profitLoss = $currentValue - $totalCost;
                    $profitLossPercentage = ($totalCost > 0) ? ($profitLoss / $totalCost) * 100 : 0;
                @endphp
                <tr>
                    <td class="py-2 px-4 border-b text-white">{{ $item->crypto_id }}</td>
                    <td class="py-2 px-4 border-b text-white">{{ $item->account_name }}</td>
                    <td class="py-2 px-4 border-b text-white">{{ $item->currency }}</td>
                    <td class="py-2 px-4 border-b text-white">{{ number_format($item->amount, 4) }}</td>
                    <td class="py-2 px-4 border-b text-white">{{ number_format($item->purchase_price, 4) }} {{ $item->currency }}</td>
                    <td class="py-2 px-4 border-b text-white">{{ number_format($currentPrice, 4) }} {{ $item->currency }}</td>
                    <td class="py-2 px-4 border-b {{ $profitLoss >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($profitLoss, 2) }} {{ $item->currency }} ({{ number_format($profitLossPercentage, 2) }}%)
                    </td>
                    <td class="py-2 px-4 border-b">
                        <form action="{{ route('crypto.sell', $item->id) }}" method="POST">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="crypto_symbol" value="{{ $item->crypto_id }}">
                            <input type="number" name="amount" placeholder="Amount to sell" class="w-full p-2 rounded border-gray-300 mb-2" step="0.01" required>
                            <input type="hidden" name="account_id" value="{{ $item->account_id }}">
                            <x-primary-button>
                                Sell
                            </x-primary-button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @if($errors->any())
        <div class="mb-4 bg-red-100 text-red-600 p-4 rounded">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
</x-app-layout>
