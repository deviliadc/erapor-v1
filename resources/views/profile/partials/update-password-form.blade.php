<div class="p-5 mb-6 border border-gray-200 dark:border-gray-700 rounded-2xl lg:p-6">
    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('PUT')

        @php
            $passwordFields = [
                ['name' => 'current_password', 'label' => 'Current Password'],
                ['name' => 'password', 'label' => 'Password Baru'],
                ['name' => 'password_confirmation', 'label' => 'Konfirmasi Password'],
            ];
        @endphp

        @foreach ($passwordFields as $field)
            <x-form.password :name="$field['name']" :label="$field['label']" />
        @endforeach

        <!-- Save Button -->
        <div class="flex justify-end items-center gap-4">
            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm text-green-600 dark:text-green-400">
                    Saved.
                </p>
            @endif
            <button type="submit"
                class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                Save Changes
            </button>
        </div>
    </form>
</div>
