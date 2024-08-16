<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Sell Cryptocurrency') }}
        </h2>
    </x-slot>

    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold mb-6 text-white">Sell Cryptocurrency</h1>

        <form action="{{ route('crypto.sell', $holding->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="amount" class="block text-white">Amount:</label>
                <input type="number" step="0.0001" name="amount" id="amount" class="w-full border-gray-300 rounded-md shadow-sm" value="{{ $holding->amount }}" required readonly>
            </div>

            <div class="mb-4">
                <label for="account_id" class="block text-white">Account ID</label>
                <input type="text" name="account_id" id="account_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
            </div>

            <div class="mb-4">
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Sell</button>
            </div>
        </form>
    </div>
</x-app-layout>
