<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <x-form.input
            id="email"
            type="email"
            name="email"
            :value="old('email')"
            required
            autofocus
            :label="'Email'"
            {{-- :error="$errors->get('email')" --}} />
        {{-- <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div> --}}

        <!-- Password with Toggle -->
        <x-form.password
            :name="'password'"
            :label="'Password'" />

        {{-- <div class="mt-4" x-data="{ show: false }">
            <x-input-label for="password" :value="__('Password')" />

            <div class="relative">
                <input :type="show ? 'text' : 'password'" id="password" name="password" required
                    autocomplete="current-password"
                    class="block w-full mt-1 pr-10 rounded-md shadow-sm border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />

                <!-- Toggle Button -->
                <button type="button" @click="show = !show"
                    class="absolute top-1/2 right-3 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                    tabindex="-1">

                    <!-- Eye Icon -->
                    <svg x-show="!show" x-cloak class="fill-gray-500 dark:fill-gray-400" width="20" height="20"
                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M10 13.86C7.23 13.86 4.87 12.14 3.92 9.7C4.87 7.27 7.23 5.54 10 5.54C12.77 5.54 15.13 7.27 16.08 9.7C15.13 12.14 12.77 13.86 10 13.86ZM10 4.04C6.48 4.04 3.49 6.31 2.42 9.46C2.36 9.62 2.36 9.79 2.42 9.95C3.49 13.1 6.48 15.36 10 15.36C13.52 15.36 16.51 13.1 17.58 9.95C17.64 9.79 17.64 9.62 17.58 9.46C16.51 6.31 13.52 4.04 10 4.04ZM9.99 7.84C8.97 7.84 8.13 8.68 8.13 9.7C8.13 10.73 8.97 11.56 9.99 11.56H10.01C11.03 11.56 11.86 10.73 11.86 9.7C11.86 8.68 11.03 7.84 10.01 7.84H9.99Z" />
                    </svg>

                    <!-- Eye Off Icon -->
                    <svg x-show="show" x-cloak class="fill-gray-500 dark:fill-gray-400" width="20" height="20"
                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M4.64 3.58C4.35 3.28 3.87 3.28 3.58 3.58C3.28 3.87 3.28 4.34 3.58 4.64L4.85 5.91C3.75 6.84 2.89 8.06 2.42 9.46C2.36 9.62 2.36 9.79 2.42 9.95C3.49 13.1 6.48 15.36 10 15.36C11.26 15.36 12.44 15.07 13.5 14.56L15.36 16.42C15.65 16.72 16.13 16.72 16.42 16.42C16.72 16.13 16.72 15.66 16.42 15.36L4.64 3.58ZM12.36 13.42L10.45 11.51C10.31 11.54 10.16 11.56 10.01 11.56H9.99C8.97 11.56 8.13 10.73 8.13 9.7C8.13 9.55 8.15 9.39 8.19 9.25L5.92 6.98C5.04 7.69 4.34 8.63 3.92 9.7C4.87 12.14 7.23 13.86 10 13.86C10.83 13.86 11.63 13.71 12.36 13.42ZM16.08 9.7C15.78 10.46 15.36 11.14 14.82 11.73L15.88 12.79C16.63 11.98 17.22 11.01 17.58 9.95C17.64 9.79 17.64 9.62 17.58 9.46C16.51 6.31 13.52 4.04 10 4.04C9.14 4.04 8.3 4.18 7.52 4.43L8.75 5.66C9.16 5.58 9.57 5.54 10 5.54C12.77 5.54 15.13 7.27 16.08 9.7Z" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div> --}}

        <!-- Remember Me -->
        {{-- <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
        </label>
        </div> --}}

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('password.request') }}">
                {{ __('Forgot your password?') }}
            </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    @if (session('session_expired'))
    <div x-data="{ sessionExpired: true }">
        {{-- <div x-show="sessionExpired" x-cloak
                class="fixed inset-0 flex items-center justify-center p-6 bg-black/50 backdrop-blur-sm overflow-y-auto modal z-99999">
<div class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"></div> --}}
        <div x-show="sessionExpired" x-cloak class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto modal z-99999">
            <div class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"></div>
            {{-- <div @click.outside="sessionExpired = false"
                    class="relative w-full max-w-sm md:max-w-md rounded-2xl bg-white dark:bg-gray-100 p-6 shadow-xl"> --}}
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
                    {{-- <svg class="w-12 h-12 text-yellow-400 dark:text-yellow-300" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.054 0 1.918-.816 1.995-1.85L21 16.118V7.882c0-1.034-.82-1.876-1.85-1.994L18.118 6H5.882C4.848 6 4.006 6.82 3.888 7.85L3.882 7.882v8.236c0 1.034.82 1.876 1.85 1.994L5.882 19z" />
                    </svg> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-12 text-yellow-500">
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
                {{-- <div class="text-center">
                        <a href="{{ route('login') }}"
                class="inline-block px-5 py-2 text-white bg-gray-500 hover:bg-black-600 rounded-lg shadow transition">
                Login Ulang
                </a>
            </div> --}}
            <!-- Button -->
            <div class="text-center">
                <button type="button" @click="sessionExpired = false"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 ms-3">
                    OK
                </button>
            </div>
        </div>
    </div>
    </div>
    @endif
</x-guest-layout>
