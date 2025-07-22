<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-5 py-4 sm:px-6 sm:py-5">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Edit User</h3>
            </div>

            <form action="{{ route('admin.user.update', $user->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
                @csrf
                @method('PUT')

                {{-- Username --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Username</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    @error('username')
                        <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    @error('email')
                        <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Fields --}}
                @php
                    $passwordFields = [
                        ['name' => 'password', 'label' => 'Password Baru'],
                        ['name' => 'password_confirmation', 'label' => 'Konfirmasi Password'],
                    ];
                @endphp

                @foreach ($passwordFields as $field)
                    <x-form.password :name="$field['name']" :label="$field['label']" />
                @endforeach

                {{-- Roles --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Roles</label>
                    <div class="flex flex-wrap gap-6">
                        @foreach ($roles as $role)
                            <label class="flex items-center text-sm text-gray-700 dark:text-gray-400">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                    {{ in_array($role->id, $user->roles->pluck('id')->toArray()) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-brand-600 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:checked:bg-brand-500" />
                                <span class="ml-2 select-none">{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Submit --}}
                <div>
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
