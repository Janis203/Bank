<!-- resources/views/investments/withdraw.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Withdraw from Investment') }}
        </h2>
    </x-slot>

    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold mb-6 text-white">Withdraw from Investment</h1>

        <form action="{{ route('investments.withdraw.post', $investment->id) }}" method="POST">
            @csrf
            @method('POST')
            <div class="mb-4">
                <p class="text-white">Current Investment Amount: ${{ number_format($investment->amount, 2) }}</p>
                <p class="text-white">Invested At: {{ $investment->invested_at->format('Y-m-d H:i:s') }}</p>
                <p class="text-white">Current Value: ${{ number_format($investment->calculateCurrentValue(), 2) }}</p>
            </div>

            <div class="mb-4">
                <label for="amount" class="block text-white text-sm font-bold mb-2">{{ __('Amount to Withdraw') }}</label>
                <input type="number" step="0.01" name="amount" id="amount" class="w-full border-gray-300 rounded-md shadow-sm" required>
            </div>

            <div class="mb-4">
                <x-primary-button>Withdraw</x-primary-button>
            </div>
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
