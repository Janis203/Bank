<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('Welcome') }}
        </h2>
    </x-slot>
    <div class="container mx-auto py-12">
        <div class="text-center">
            <p class="text-lg mb-8 text-white">Create an Account or Log In</p>
            @guest
                <x-primary-button>
                <a href="{{ route('login') }}"
                   class="font-bold py-2 px-4">
                    Login
                </a>
                </x-primary-button>
                <x-primary-button>
                <a href="{{ route('register') }}"
                   class="font-bold py-2 px-4">
                    Register
                </a>
                </x-primary-button>
            @endguest
        </div>
    </div>
</x-app-layout>
