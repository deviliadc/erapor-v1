<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Lupa kata sandi? Silakan masukkan alamat email Anda yang terdaftar.
        Sistem akan mengirimkan tautan pengaturan ulang kata sandi guna memungkinkan Anda membuat kata sandi baru.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <!-- Email Address -->
        <x-form.input
            label="Email"
            name="email"
            type="email"
            :value="old('email')"
            required
            autofocus
        />
        <div class="flex items-center justify-end mt-6 gap-4">
            <x-primary-button>
                Kirim Link Reset
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
