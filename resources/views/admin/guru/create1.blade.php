<x-app-layout>
    <!-- Breadcrumb -->
    <x-breadcrumb title="Create Guru" />

    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-5 py-4 sm:px-6 sm:py-5">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                    Create Guru
                </h3>
            </div>

            <form action="{{ route('admin.guru.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
                @csrf

                {{-- Nama --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Nama
                    </label>
                    <input type="text" name="nama"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        value="{{ old('nama') }}">
                    @error('nama')
                        <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- NIP --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        NIP
                    </label>
                    <input type="text" name="nip" placeholder="NIP"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        value="{{ old('nip') }}">
                    @error('nip')
                        <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- No HP --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        No HP
                    </label>
                    <input type="text" name="no_hp"  placeholder="628xxxxxxxxxx"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        value="{{ old('no_hp') }}">
                    @error('no_hp')
                        <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Alamat
                    </label>
                    <input type="text" name="alamat"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        value="{{ old('alamat') }}">
                    @error('alamat')
                        <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jenis Kelamin --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Jenis Kelamin
                    </label>
                    <select name="jenis_kelamin"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                    >
                        <option value="" disabled selected>Pilih jenis kelamin</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
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
