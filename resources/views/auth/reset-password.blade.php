<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}"
    class="space-y-6 sm:p-6">
        @csrf
        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <!-- Email Address -->
        <x-form.input
            label="Email"
            name="email"
            type="email"
            :value="old('email', $request->email)"
            required
            autofocus
            autocomplete="username"
        />
        <!-- Password -->
        <div class="mt-4">
            <x-form.password
                label="Password Baru"
                name="password"
                required
                autocomplete="new-password"
            />
        </div>
        <!-- Confirm Password -->
        <div class="mt-4">
            <x-form.password
                label="Konfirmasi Password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />
        </div>
        <div class="flex items-center justify-end mt-6 gap-4">
            <x-primary-button>
                Reset Password
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
