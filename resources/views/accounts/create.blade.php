@php use App\Models\Accounts; @endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create an account') }}
        </h2>
    </x-slot>
    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold mb-6 text-white">Create New Account</h1>
        <form action="{{ route('accounts.store') }}" method="POST"
              class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            @csrf
            <div class="mb-4">
                <label for="type" class="block text-gray-700 dark:text-gray-300">Account Type</label>
                <select name="type" id="type" class="w-full mt-2 p-2 border rounded">
                    @foreach(Accounts::TYPES as $type)
                        <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="currency" class="block text-sm font-medium text-gray-700">Select Currency</label>
                <select name="currency" id="currency" required class="block w-full mt-1">
                    @foreach($fiatCurrencies as $code => $name)
                        <option value="{{ $code }}">{{ $name }} ({{ $code }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="name" class="block text-gray-700 dark:text-gray-300">Account Name</label>
                <input type="text" id="name" name="name" class="w-full mt-2 p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="balance" class="block text-gray-700 dark:text-gray-300">Initial Balance</label>
                <input type="number" id="balance" name="balance" class="w-full mt-2 p-2 border rounded" required>
            </div>
            <x-primary-button>Create Account</x-primary-button>
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
