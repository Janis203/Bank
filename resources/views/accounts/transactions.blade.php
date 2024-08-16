<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transactions') }}
        </h2>
    </x-slot>

    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold mb-6 text-white">Transactions for {{ $account->name }}</h1>

        <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-white">
            <thead>
            <tr>
                <th class="py-2 px-4 border-b">{{ __('Date') }}</th>
                <th class="py-2 px-4 border-b">{{ __('From Account') }}</th>
                <th class="py-2 px-4 border-b">{{ __('To Account') }}</th>
                <th class="py-2 px-4 border-b">{{ __('Amount') }}</th>
                <th class="py-2 px-4 border-b">{{ __('Message') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                    <td class="py-2 px-4 border-b">{{ $transaction->fromAccount->name }}</td>
                    <td class="py-2 px-4 border-b">{{ $transaction->toAccount->name ?? '-' }}</td>
                    <td class="py-2 px-4 border-b">{{ number_format($transaction->amount, 2) }}</td>
                    <td class="py-2 px-4 border-b">{{ $transaction->message ?? '-' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
