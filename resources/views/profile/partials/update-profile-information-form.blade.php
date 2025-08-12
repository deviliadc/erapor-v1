@php
    $fullName = $user->siswa?->nama ?? $user->guru?->nama ?? '-';
@endphp
<!-- Profile Info -->
<div class="p-5 mb-6 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-2xl lg:p-6">
    <h3 class="text-base pb-3 font-medium text-gray-800 dark:text-white/90">
        Profile Information
    </h3>
        <!-- Profile Form -->
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
            class="w-full max-w-xl space-y-5 pt-4 border-t border-gray-200 dark:border-gray-700">
            @csrf
            @method('PATCH')
            <!-- Username (readonly) -->
            <x-form.input name="username" label="Username" :value="$user->username" readonly
                class="cursor-not-allowed bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400" />
            <!-- Full Name (readonly) -->
            <x-form.input name="name" label="Full Name" :value="$fullName" readonly
                class="cursor-not-allowed bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400" />
            <!-- Email -->
            <x-form.input name="email" label="Email" type="email" :value="old('email', $user->email)" required />
            <!-- Phone -->
            <x-form.input name="phone" label="Phone" :value="old('phone', $user->phone)" />
            <!-- Save Button -->
            <div class="flex justify-end items-center gap-4">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 hover:bg-brand-600 shadow">
                    Save
                </button>
                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-green-600 dark:text-green-400">
                        {{ __('Saved.') }}
                    </p>
                @endif
            </div>
        </form>
</div>
