<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transfer Money') }}
        </h2>
    </x-slot>

    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold mb-6 text-white">Transfer Money</h1>

        <form action="{{ route('accounts.transfer') }}" method="POST" onsubmit="return validateForm()">
            @csrf
            <input type="hidden" name="from_account_id" id="from_account_id" value="{{ $account->id }}">

            <div class="mb-4">
                <label for="to_account_id_select" class="block text-white text-sm font-bold mb-2">{{ __('Transfer To') }}</label>

                <select name="to_account_id_select" id="to_account_id_select" class="w-full p-2 rounded border-gray-300">
                    <option value="">Select an IBAN</option>
                    @foreach($accounts as $acc)
                        <option value="{{ $acc->iban }}">{{ $acc->name }} ({{ $acc->currency }})</option>
                    @endforeach
                </select>

                <label for="to_account_id" class="block text-white text-sm font-bold mt-4">{{ __('Or Enter IBAN') }}</label>
                <input type="text" name="to_account_id" id="to_account_id" class="w-full p-2 rounded border-gray-300 mt-1" placeholder="Enter IBAN manually if not listed">
            </div>

            <div class="mb-4">
                <label for="amount" class="block text-white text-sm font-bold mb-2">{{ __('Amount') }}</label>
                <input type="number" step="0.01" name="amount" id="amount" class="w-full border-gray-300 rounded-md shadow-sm" required>
            </div>

            <div class="mb-4">
                <label for="message" class="block text-white text-sm font-bold mb-2">{{ __('Message (Optional)') }}</label>
                <textarea name="message" id="message" rows="3" class="w-full border-gray-300 rounded-md shadow-sm"></textarea>
            </div>

            <div class="mb-4 bg-blue-500 text-white rounded hover:bg-blue-600">
                <x-primary-button>Transfer</x-primary-button>
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

    <script>
        function validateForm() {
            const select = document.getElementById('to_account_id_select').value;
            const input = document.getElementById('to_account_id').value;

            if (select === "" && input === "") {
                alert("Please select an IBAN from the list or enter one manually.");
                return false;
            }
            return true;
        }
    </script>
</x-app-layout>
