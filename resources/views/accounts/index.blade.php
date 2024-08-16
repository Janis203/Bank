<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Accounts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 text-green-600 p-4 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="py-4">
                        <x-primary-button>
                            <a href="{{ route('accounts.create') }}" class="text-red-600 hover:underline">Create
                                Account</a>
                        </x-primary-button>
                    </div>
                    <table class="min-w-full bg-gray-700 text-gray-100 border border-gray-600 rounded-lg">
                        <thead>
                        <tr>
                            <th class="py-2 px-4 border-b border-gray-600 text-white">{{ __('Name') }}</th>
                            <th class="py-2 px-4 border-b border-gray-600 text-white">{{ __('Type') }}</th>
                            <th class="py-2 px-4 border-b border-gray-600 text-white">{{ __('Currency') }}</th>
                            <th class="py-2 px-4 border-b border-gray-600 text-white">{{ __('IBAN') }}</th>
                            <th class="py-2 px-4 border-b border-gray-600 text-white">{{ __('Balance') }}</th>
                            <th class="py-2 px-4 border-b border-gray-600 text-white">{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($accounts as $account)
                            <tr>
                                <td class="py-2 px-4 border-b border-gray-600 text-white">{{ $account->name }}</td>
                                <td class="py-2 px-4 border-b border-gray-600 text-white">{{ $account->type }}</td>
                                <td class="py-2 px-4 border-b border-gray-600 text-white">{{ $account->currency }}</td>
                                <td class="py-2 px-4 border-b border-gray-600 text-white">{{ $account->iban }}</td>
                                <td class="py-2 px-4 border-b border-gray-600 text-white">{{ number_format($account->balance, 2) }}</td>
                                <td class="py-2 px-4 border-b border-gray-600 flex space-x-2">
                                    <div class="py-2 px-4">
                                    <x-primary-button>
                                        <a href="{{ route('accounts.transactions', $account) }}"
                                           class="text-blue-600 hover:underline">View Transactions</a>
                                    </x-primary-button>
                                    </div>
                                    <div class="py-2 px-4">
                                    <x-primary-button>
                                        <a href="{{ route('accounts.transferForm', ['account_id' => $account->id]) }}"
                                           class="text-green-600 hover:underline">Transfer</a>
                                    </x-primary-button>
                                    </div>
                                    <div class="py-2 px-4">
                                    <x-primary-button>
                                        <a href="{{ route('accounts.edit', ['account' => $account->id]) }}"
                                           class="text-red-600 hover:underline">Edit</a>
                                    </x-primary-button>
                                    </div>
                                    <div class="py-2 px-4">
                                        @if($account->type === 'investment')
                                            <x-primary-button>
                                            <a href="{{ route('accounts.invest', $account->id) }}" class="text-blue-500 hover:underline">Invest</a>
                                            </x-primary-button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
