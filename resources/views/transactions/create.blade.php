<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Send Money') }}
        </h2>
    </x-slot>

    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold mb-6 text-white">Send Money</h1>
        <form action="{{ route('transactions.store') }}" method="POST"
              class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            @csrf
            <div class="mb-4">
                <label for="account_id" class="block text-gray-700 dark:text-gray-300">Account</label>
                <select name="account_id" id="account_id" class="w-full mt-2 p-2 border rounded">
                    @foreach (Auth::user()->accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="recipient" class="block text-gray-700 dark:text-gray-300">Recipient</label>
                <input type="text" id="recipient" name="recipient" class="w-full mt-2 p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="amount" class="block text-gray-700 dark:text-gray-300">Amount</label>
                <input type="number" id="amount" name="amount" class="w-full mt-2 p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 dark:text-gray-300">Description</label>
                <textarea id="description" name="description" class="w-full mt-2 p-2 border rounded" rows="3"></textarea>
            </div>
            <div class="mb-4">
                <label for="recipient_message" class="block text-gray-700 dark:text-gray-300">Message to Recipient</label>
                <textarea id="recipient_message" name="recipient_message" class="w-full mt-2 p-2 border rounded" rows="3"></textarea>
            </div>
            <x-primary-button>Send Money</x-primary-button>
        </form>
    </div>
</x-app-layout>
