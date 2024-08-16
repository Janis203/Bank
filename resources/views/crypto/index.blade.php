@php
    use App\Services\CoinMarketCapService;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cryptocurrency Market') }}
        </h2>
    </x-slot>

    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold mb-6 text-white">Top 10 Cryptocurrencies</h1>

        <div class="mb-6">
            <form action="{{ route('crypto.search') }}" method="GET" id="searchForm" class="flex">
                <input type="text" name="search" id="search" placeholder="Search Cryptocurrency..." class="w-full p-2 rounded-l border-gray-300">
                <button type="submit" class="p-2 bg-blue-500 text-white rounded-r hover:bg-blue-600">Search</button>
            </form>
        </div>

        <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-white">
            <thead>
            <tr>
                <th class="py-2 px-4 border-b">{{ __('Rank') }}</th>
                <th class="py-2 px-4 border-b">{{ __('Crypto') }}</th>
                <th class="py-2 px-4 border-b">{{ __('Symbol') }}</th>
                <th class="py-2 px-4 border-b">{{ __('Current Price') }}</th>
                <th class="py-2 px-4 border-b">{{ __('Currency') }}</th>
                <th class="py-2 px-4 border-b">{{ __('Last Updated') }}</th>
                <th class="py-2 px-4 border-b">{{ __('Action') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($topCryptos as $crypto)
                <tr>
                    <td class="py-2 px-4 border-b text-white">{{ $crypto['rank'] ?? 'N/A' }}</td>
                    <td class="py-2 px-4 border-b text-white">{{ $crypto['name'] }}</td>
                    <td class="py-2 px-4 border-b text-white">{{ $crypto['symbol'] }}</td>
                    <td class="py-2 px-4 border-b text-white">${{ number_format($crypto['current_price'], 8) }}</td>
                    <td class="py-2 px-4 border-b text-white">{{ $crypto['currency'] ?? 'USD' }}</td>
                    <td class="py-2 px-4 border-b text-white">{{ \Carbon\Carbon::parse($crypto['last_updated'])->format('Y-m-d H:i') }}</td>
                    <td class="py-2 px-4 border-b text-white">
                        <a href="{{ route('crypto.buyForm', ['crypto_id' => $crypto['id']]) }}" class="text-blue-500 hover:underline">Buy</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <script>
        document.getElementById('searchForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const query = document.getElementById('search').value;

            if (query.trim() !== '') {
                fetch(`/crypto/search?search=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        const tbody = document.querySelector('tbody');
                        tbody.innerHTML = '';

                        data.results.forEach(crypto => {
                            const row = `<tr>
                                <td class="py-2 px-4 border-b">${crypto.rank ?? 'N/A'}</td>
                                <td class="py-2 px-4 border-b">${crypto.name}</td>
                                <td class="py-2 px-4 border-b">${crypto.symbol}</td>
                                <td class="py-2 px-4 border-b">$${crypto.current_price.toFixed(2)}</td>
                                <td class="py-2 px-4 border-b">${crypto.currency ?? 'USD'}</td>
                                <td class="py-2 px-4 border-b">${crypto.last_updated ? new Date(crypto.last_updated).toLocaleString() : 'N/A'}</td>
                                <td class="py-2 px-4 border-b">
                                    <a href="/crypto/buyForm?crypto_id=${crypto.id}" class="text-blue-500 hover:underline">Buy</a>
                                </td>
                            </tr>`;
                            tbody.innerHTML += row;
                        });
                    })
                    .catch(error => console.error('Error fetching search results:', error));
            }
        });
    </script>
</x-app-layout>
