<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 ">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="bg-white dark:bg-gray-600 p-6 rounded-lg shadow flex">
                    <div class="bg-white dark:bg-gray-600 p-6 rounded-lg shadow flex-column">
                    <h2 class="text-xl font-semibold mb-4">Accounts</h2>
                    <x-primary-button>
                        <a href="{{ route('accounts.index') }}">View accounts</a>
                    </x-primary-button>
                    </div>
                    <div class="bg-white dark:bg-gray-600 p-6 rounded-lg shadow flex-column">
                    <ul style="list-style-type:circle">
                        <li>Create new accounts</li>
                        <li>Transfer funds between accounts</li>
                        <li>View transactions</li>
                    </ul>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-600 p-6 rounded-lg shadow flex">
                    <div class="bg-white dark:bg-gray-600 p-6 rounded-lg shadow flex-column">
                    <h2 class="text-xl font-semibold mb-4">Crypto Currencies</h2>
                    <x-primary-button>
                        <a href="{{ route('crypto.index') }}">View crypto</a>
                    </x-primary-button>
                    </div>
                    <div class="bg-white dark:bg-gray-600 p-6 rounded-lg shadow flex-column">
                        <ul style="list-style-type:circle">
                            <li>Check crypto prices</li>
                            <li>Buy Crypto</li>
                            <li>Search for any crypto in the database</li>
                        </ul>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-600 p-6 rounded-lg shadow flex">
                    <div class="bg-white dark:bg-gray-600 p-6 rounded-lg shadow flex-column">
                    <h2 class="text-xl font-semibold mb-4">Portfolio</h2>
                    <x-primary-button>
                        <a href="{{ route('crypto.portfolio') }}">View portfolio</a>
                    </x-primary-button>
                    </div>
                    <div class="bg-white dark:bg-gray-600 p-6 rounded-lg shadow flex-column">
                        <ul style="list-style-type:circle">
                            <li>Check Profit/Loss</li>
                            <li>Buy/Sell crypto</li>
                            <li>View your accounts portfolio</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
