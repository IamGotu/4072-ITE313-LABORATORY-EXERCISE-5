<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h2 class="text-center text-2xl font-semibold text-gray-700 mb-6">{{ __('Welcome!') }}</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email Address')" class="text-lg font-medium text-gray-800" />
            <x-text-input id="email" class="block mt-1 w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500" />
        </div>

        <!-- Password -->
        <div class="mb-6">
            <x-input-label for="password" :value="__('Your Password')" class="text-lg font-medium text-gray-800" />
            <x-text-input id="password" class="block mt-1 w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mb-6">
            @if (Route::has('register'))
                <a class="text-sm text-gray-600 hover:text-gray-900 font-medium" href="{{ route('register') }}">
                    {{ __('Need an account? Register') }}
                </a>
            @endif

            @if (Route::has('password.request'))
                <a class="text-sm text-gray-600 hover:text-gray-900 font-medium" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <div class="mt-6 text-center">
            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg mt-3">
                {{ __('Sign In') }}
            </button>
        </div>
    </form>
</x-guest-layout>
