<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-5 py-4 sm:px-6 sm:py-5">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Data Siswa</h3>
            </div>

            <form action="{{ role_route('siswa.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
                @csrf

                {{-- Nama --}}
                <x-form.input label="Nama" name="nama" :value="old('nama')" required />

                {{-- NIS --}}
                <x-form.input label="NIS" name="nis" :value="old('nis')" required />

                {{-- NISN --}}
                <x-form.input label="NISN" name="nisn" :value="old('nisn')" required />

                {{-- Jenis Kelamin --}}
                <x-form.select
                    label="Jenis Kelamin"
                    name="jenis_kelamin"
                    :options="['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan']"
                    placeholder="Pilih jenis kelamin"
                    :selected="old('jenis_kelamin')"
                    :searchable="true"
                    required
                />

                {{-- Tempat Lahir --}}
                <x-form.input label="Tempat Lahir" name="tempat_lahir" :value="old('tempat_lahir')" required />

                {{-- Tanggal Lahir --}}
                <x-form.date-picker label="Tanggal Lahir" name="tanggal_lahir" :value="old('tanggal_lahir')"
                    placeholder="Pilih tanggal" class="datepicker" required />

                {{-- Pendidikan Sebelumnya --}}
                <x-form.input label="Pendidikan Sebelumnya" name="pendidikan_sebelumnya" :value="old('pendidikan_sebelumnya')" />

                {{-- Alamat --}}
                <x-form.textarea label="Alamat" name="alamat" placeholder="Masukkan alamat" rows="4" required>
                    {{ old('alamat') }} required
                </x-form.textarea>

                {{-- No HP --}}
                <x-form.input label="No HP" name="no_hp" :value="old('no_hp')" placeholder="628xxxxxxxxxx" />

                {{-- Email --}}
                <x-form.input label="Email" name="email" :value="old('email')" type="email" />

                {{-- Status --}}
                <x-form.select
                    label="Status"
                    name="status"
                    :options="['Aktif' => 'Aktif', 'Lulus' => 'Lulus', 'Keluar' => 'Keluar', 'Mutasi' => 'Mutasi']"
                    placeholder="Pilih status"
                    :selected="old('status')"
                    :searchable="true"
                    required
                />

                <div class="px-5 py-4 sm:px-6 sm:py-5 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Data Wali Murid</h3>
                </div>

                {{-- Pilih Wali --}}
                @php
                    $waliOptions = $wali_list->mapWithKeys(function ($wali) {
                        $labelParts = [];

                        if (!empty($wali->nama_ayah)) {
                            $labelParts[] = $wali->nama_ayah;
                        }

                        if (!empty($wali->nama_ibu)) {
                            $labelParts[] = $wali->nama_ibu;
                        }

                        if (!empty($wali->nama_wali)) {
                            $labelParts[] = $wali->nama_wali;
                        }

                        return [
                            $wali->id => implode(' - ', $labelParts),
                        ];
                    });

                    $waliOptions['baru'] = '+ Tambah Wali Baru';
                @endphp

                <x-form.select label="Pilih Wali" name="wali_murid_id" :options="$waliOptions"
                    placeholder="-- Pilih Wali --" selected="{{ old('wali_murid_id') }}"
                    onchange="toggleWaliForm(this.value)" />

                <div id="wali_baru_inputs" style="display: none;" class="space-y-6 mt-4">
                    {{-- Nama Ayah --}}
                    <x-form.input label="Nama Ayah" name="nama_ayah" :value="old('nama_ayah')" />

                    {{-- Nama Ibu --}}
                    <x-form.input label="Nama Ibu" name="nama_ibu" :value="old('nama_ibu')" />

                    {{-- Nama Wali --}}
                    <x-form.input label="Nama Wali" name="nama_wali" :value="old('nama_wali')" />

                    {{-- No HP --}}
                    <x-form.input label="No HP" name="no_hp_wali" :value="old('no_hp_wali')" placeholder="628xxxxxxxxxx" />

                    {{-- Pekerjaan Ayah --}}
                    <x-form.input label="Pekerjaan Ayah" name="pekerjaan_ayah" :value="old('pekerjaan_ayah')" />

                    {{-- Pekerjaan Ibu --}}
                    <x-form.input label="Pekerjaan Ibu" name="pekerjaan_ibu" :value="old('pekerjaan_ibu')" />

                    {{-- Pekerjaan Wali --}}
                    <x-form.input label="Pekerjaan Wali" name="pekerjaan_wali" :value="old('pekerjaan_wali')" />

                    {{-- Alamat --}}
                    <x-form.textarea label="Alamat" name="alamat_wali" placeholder="Masukkan alamat" rows="4">
                        {{ old('alamat_wali') }}
                    </x-form.textarea>
                </div>

                {{-- Submit --}}
                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                        Tambah
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>

<script>
    function toggleWaliForm(value) {
        const waliBaruInputs = document.getElementById('wali_baru_inputs');
        const waliFormInputs = document.getElementById('wali_form_inputs');
        const isBaru = value === 'baru';

        if (waliBaruInputs) waliBaruInputs.style.display = isBaru ? 'block' : 'none';
        if (waliFormInputs) waliFormInputs.style.display = isBaru ? 'block' : 'none';
    }
</script>

<style>
.flatpickr-day.disabled,
.flatpickr-day.flatpickr-disabled,
.flatpickr-day:not(.prevMonthDay):not(.nextMonthDay):not(.today).flatpickr-disabled {
    background: #f3f4f6 !important; /* abu-abu (gray-100) */
    color: #b0b0b0 !important;
    cursor: not-allowed !important;
    opacity: 1 !important;
}
</style>
