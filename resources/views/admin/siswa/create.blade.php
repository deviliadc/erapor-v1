<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-5 py-4 sm:px-6 sm:py-5">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Create Siswa</h3>
            </div>

            <form action="{{ route('admin.siswa.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
                @csrf

                {{-- Nama --}}
                <x-form.input label="Nama" name="nama" :value="old('nama')" />

                {{-- NIS --}}
                <x-form.input label="NIS" name="nis" :value="old('nis')" />

                {{-- NISN --}}
                <x-form.input label="NISN" name="nisn" :value="old('nisn')" />

                {{-- Jenis Kelamin --}}
                <x-form.select label="Jenis Kelamin" name="jenis_kelamin" :options="['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan']"
                    placeholder="Pilih jenis kelamin" :selected="old('jenis_kelamin')" />

                {{-- Tempat Lahir --}}
                <x-form.input label="Tempat Lahir" name="tempat_lahir" :value="old('tempat_lahir')" />

                {{-- Tanggal Lahir --}}
                <x-form.datepicker label="Tanggal Lahir" name="tanggal_lahir" :value="old('tanggal_lahir')"
                    placeholder="Pilih tanggal" />

                {{-- Pendidikan Sebelumnya --}}
                <x-form.input label="Pendidikan Sebelumnya" name="pendidikan_sebelumnya" :value="old('pendidikan_sebelumnya')" />

                {{-- Alamat --}}
                <x-form.input label="Alamat" name="alamat" :value="old('alamat')" />

                {{-- No HP --}}
                <x-form.input label="No HP" name="no_hp" :value="old('no_hp')" placeholder="628xxxxxxxxxx" />

                {{-- Email --}}
                <x-form.input label="Email" name="email" :value="old('email')" type="email" />

                {{-- Submit --}}
                <div>
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                        Tambah
                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M9.25 5C9.25 4.59 9.59 4.25 10 4.25C10.41 4.25 10.75 4.59 10.75 5V9.25H15C15.41 9.25 15.75 9.59 15.75 10C15.75 10.41 15.41 10.75 15 10.75H10.75V15C10.75 15.41 10.41 15.75 10 15.75C9.59 15.75 9.25 15.41 9.25 15V10.75H5C4.59 10.75 4.25 10.41 4.25 10C4.25 9.59 4.59 9.25 5 9.25H9.25V5Z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
