<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6 sm:p-6">
        @csrf
        <!-- Email Address -->
        <x-form.input id="login" type="text" name="login" :value="old('login')" required autofocus
            :label="'Email atau Username'" placeholder="Email atau Username"/>

        <!-- Password with Toggle -->
        <x-form.password :name="'password'" :label="'Password'" required />

        <div class="flex items-center justify-end mt-6 gap-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md
                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('password.request') }}">
                    {{ 'Forgot your password?' }}
                </a>
            @endif
            <x-primary-button class="ms-3 p-3">
                {{ 'Log in' }}
            </x-primary-button>
        </div>
    </form>

    @if (session('session_expired'))
        <div x-data="{ sessionExpired: true }">
            <div x-show="sessionExpired" x-cloak
                class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto modal z-99999">
                <div class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"></div>
                <div @click.outside="sessionExpired = false"
                    class="relative max-w-fit md:max-w-md rounded-2xl bg-white dark:bg-gray-100 p-6 shadow-xl text-center">
                    <!-- Close Button -->
                    <button @click="sessionExpired = false"
                        class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-600 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <!-- Icon -->
                    <div class="flex justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-8 h-8 text-yellow-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <!-- Title -->
                    <h2 class="text-center text-lg font-semibold text-gray-800 dark:text-gray-600 mb-2">
                        Sesi Telah Berakhir
                    </h2>
                    <!-- Message -->
                    <p class="text-center text-sm text-gray-600 dark:text-gray-400 mb-6">
                        {{ session('session_expired') }}
                    </p>
                    <!-- Button -->
                    <div class="text-center">
                        <button type="button" @click="sessionExpired = false"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent
                            rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
                            focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500
                            focus:ring-offset-2 transition ease-in-out duration-150 ms-3">
                            OK
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-guest-layout>
