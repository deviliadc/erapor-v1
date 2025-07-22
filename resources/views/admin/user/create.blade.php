<x-app-layout>
    <!-- Breadcrumb -->
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-5 py-4 sm:px-6 sm:py-5">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                    Create User
                </h3>
            </div>

            <form action="{{ route('admin.user.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
                @csrf

                {{-- Username --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Username
                    </label>
                    <input type="text" name="username" value="{{ old('username') }}" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    @error('username')
                        <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Email
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="email@gmail.com"
                        required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                    @error('email')
                        <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div x-data="{ showPassword: false }">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Password
                    </label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" name="password" placeholder="Enter password"
                            required
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />

                        <span @click="showPassword = !showPassword"
                            class="absolute top-1/2 right-4 -translate-y-1/2 cursor-pointer">
                            {{-- Eye Icon --}}
                            <svg x-show="!showPassword" x-cloak class="fill-gray-500 dark:fill-gray-400" width="20"
                                height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M10 13.86C7.23 13.86 4.87 12.14 3.92 9.7C4.87 7.27 7.23 5.54 10 5.54C12.77 5.54 15.13 7.27 16.08 9.7C15.13 12.14 12.77 13.86 10 13.86ZM10 4.04C6.48 4.04 3.49 6.31 2.42 9.46C2.36 9.62 2.36 9.79 2.42 9.95C3.49 13.1 6.48 15.36 10 15.36C13.52 15.36 16.51 13.1 17.58 9.95C17.64 9.79 17.64 9.62 17.58 9.46C16.51 6.31 13.52 4.04 10 4.04ZM9.99 7.84C8.97 7.84 8.13 8.68 8.13 9.7C8.13 10.73 8.97 11.56 9.99 11.56H10.01C11.03 11.56 11.86 10.73 11.86 9.7C11.86 8.68 11.03 7.84 10.01 7.84H9.99Z" />
                            </svg>
                            {{-- Eye Off Icon --}}
                            <svg x-show="showPassword" x-cloak class="fill-gray-500 dark:fill-gray-400" width="20"
                                height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.64 3.58C4.35 3.28 3.87 3.28 3.58 3.58C3.28 3.87 3.28 4.34 3.58 4.64L4.85 5.91C3.75 6.84 2.89 8.06 2.42 9.46C2.36 9.62 2.36 9.79 2.42 9.95C3.49 13.1 6.48 15.36 10 15.36C11.26 15.36 12.44 15.07 13.5 14.56L15.36 16.42C15.65 16.72 16.13 16.72 16.42 16.42C16.72 16.13 16.72 15.66 16.42 15.36L4.64 3.58ZM12.36 13.42L10.45 11.51C10.31 11.54 10.16 11.56 10.01 11.56H9.99C8.97 11.56 8.13 10.73 8.13 9.7C8.13 9.55 8.15 9.39 8.19 9.25L5.92 6.98C5.04 7.69 4.34 8.63 3.92 9.7C4.87 12.14 7.23 13.86 10 13.86C10.83 13.86 11.63 13.71 12.36 13.42ZM16.08 9.7C15.78 10.46 15.36 11.14 14.82 11.73L15.88 12.79C16.63 11.98 17.22 11.01 17.58 9.95C17.64 9.79 17.64 9.62 17.58 9.46C16.51 6.31 13.52 4.04 10 4.04C9.14 4.04 8.3 4.18 7.52 4.43L8.75 5.66C9.16 5.58 9.57 5.54 10 5.54C12.77 5.54 15.13 7.27 16.08 9.7Z" />
                            </svg>
                        </span>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div x-data="{ showConfirm: false }">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Confirm Password
                    </label>
                    <div class="relative">
                        <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required
                            placeholder="Confirm password"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        <span @click="showConfirm = !showConfirm"
                            class="absolute top-1/2 right-4 -translate-y-1/2 cursor-pointer">
                            {{-- Eye Icon --}}
                            <svg x-show="!showPassword" x-cloak class="fill-gray-500 dark:fill-gray-400" width="20"
                                height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M10 13.86C7.23 13.86 4.87 12.14 3.92 9.7C4.87 7.27 7.23 5.54 10 5.54C12.77 5.54 15.13 7.27 16.08 9.7C15.13 12.14 12.77 13.86 10 13.86ZM10 4.04C6.48 4.04 3.49 6.31 2.42 9.46C2.36 9.62 2.36 9.79 2.42 9.95C3.49 13.1 6.48 15.36 10 15.36C13.52 15.36 16.51 13.1 17.58 9.95C17.64 9.79 17.64 9.62 17.58 9.46C16.51 6.31 13.52 4.04 10 4.04ZM9.99 7.84C8.97 7.84 8.13 8.68 8.13 9.7C8.13 10.73 8.97 11.56 9.99 11.56H10.01C11.03 11.56 11.86 10.73 11.86 9.7C11.86 8.68 11.03 7.84 10.01 7.84H9.99Z" />
                            </svg>
                            {{-- Eye Off Icon --}}
                            <svg x-show="showPassword" x-cloak class="fill-gray-500 dark:fill-gray-400" width="20"
                                height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.64 3.58C4.35 3.28 3.87 3.28 3.58 3.58C3.28 3.87 3.28 4.34 3.58 4.64L4.85 5.91C3.75 6.84 2.89 8.06 2.42 9.46C2.36 9.62 2.36 9.79 2.42 9.95C3.49 13.1 6.48 15.36 10 15.36C11.26 15.36 12.44 15.07 13.5 14.56L15.36 16.42C15.65 16.72 16.13 16.72 16.42 16.42C16.72 16.13 16.72 15.66 16.42 15.36L4.64 3.58ZM12.36 13.42L10.45 11.51C10.31 11.54 10.16 11.56 10.01 11.56H9.99C8.97 11.56 8.13 10.73 8.13 9.7C8.13 9.55 8.15 9.39 8.19 9.25L5.92 6.98C5.04 7.69 4.34 8.63 3.92 9.7C4.87 12.14 7.23 13.86 10 13.86C10.83 13.86 11.63 13.71 12.36 13.42ZM16.08 9.7C15.78 10.46 15.36 11.14 14.82 11.73L15.88 12.79C16.63 11.98 17.22 11.01 17.58 9.95C17.64 9.79 17.64 9.62 17.58 9.46C16.51 6.31 13.52 4.04 10 4.04C9.14 4.04 8.3 4.18 7.52 4.43L8.75 5.66C9.16 5.58 9.57 5.54 10 5.54C12.77 5.54 15.13 7.27 16.08 9.7Z" />
                            </svg>
                        </span>
                    </div>
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Roles --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Roles
                    </label>
                    <div class="flex flex-wrap gap-6">
                        @foreach ($roles as $role)
                            <label class="flex items-center space-x-2 text-sm text-gray-700 dark:text-gray-400">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                    {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-brand-600 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:checked:bg-brand-500" />
                                <span>{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Submit Button --}}
                <div>
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                        Tambah
                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M9.25 5C9.25 4.59 9.59 4.25 10 4.25C10.41 4.25 10.75 4.59 10.75 5V9.25H15C15.41 9.25 15.75 9.59 15.75 10C15.75 10.41 15.41 10.75 15 10.75H10.75V15C10.75 15.41 10.41 15.75 10 15.75C9.59 15.75 9.25 15.41 9.25 15V10.75H5C4.59 10.75 4.25 10.41 4.25 10C4.25 9.59 4.59 9.25 5 9.25H9.25V5Z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
