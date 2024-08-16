<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Account') }}
        </h2>
    </x-slot>

    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold mb-6 text-white">Edit Account</h1>

        <form action="{{ route('accounts.update', $account->id) }}" method="POST" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label for="name" class="block text-white text-sm font-bold mb-2">{{ __('Account Name') }}</label>
                <input type="text" name="name" id="name" class="w-full p-2 rounded border-gray-300" value="{{ old('name', $account->name) }}" required>
            </div>

            <div class="mb-4">
                <label for="balance" class="block text-white text-sm font-bold mb-2">{{ __('Balance') }}</label>
                <input type="number" name="balance" id="balance" class="w-full p-2 rounded border-gray-300" value="{{ old('balance', $account->balance) }}" step="0.01" required>
            </div>

            <div class="mb-4">
                <label class="block text-white text-sm font-bold mb-2">{{ __('Currency') }}</label>
                <p class="text-white">{{ $account->currency }}</p>
            </div>

            <x-primary-button>Update Account</x-primary-button>
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
