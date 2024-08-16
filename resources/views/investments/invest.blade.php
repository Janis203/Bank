<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Invest in Account') }}
        </h2>
    </x-slot>

    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold mb-6 text-white">Invest in {{ $account->name }}</h1>

        <form action="{{ route('investments.invest') }}" method="POST">
            @csrf
            <input type="hidden" name="account_id" value="{{ $account->id }}">

            <div class="mb-4">
                <label for="amount" class="block text-white text-sm font-bold mb-2">{{ __('Amount') }}</label>
                <input type="number" step="0.01" name="amount" id="amount" class="w-full border-gray-300 rounded-md shadow-sm" required>
            </div>

            <x-primary-button>Invest</x-primary-button>
        </form>
    </div>
</x-app-layout>
