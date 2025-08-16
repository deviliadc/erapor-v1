<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-5 py-4 sm:px-6 sm:py-5">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Edit Siswa</h3>
            </div>

            <form action="{{ role_route('siswa.update', ['siswa' => $siswa['id']]) }}" method="POST"
                enctype="multipart/form-data" class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <x-form.input label="Nama" name="nama" :value="old('nama', $siswa->nama)" required />

                {{-- NIPD --}}
                <x-form.input label="NIPD" name="nipd" :value="old('nipd', $siswa->nipd)" required />

                {{-- NISN --}}
                <x-form.input label="NISN" name="nisn" :value="old('nisn', $siswa->nisn)" required />

                {{-- Jenis Kelamin --}}
                <x-form.select label="Jenis Kelamin" name="jenis_kelamin" :options="['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan']"
                    placeholder="Pilih jenis kelamin" :selected="old('jenis_kelamin', $siswa->jenis_kelamin ?? null)" :searchable="true" required />

                {{-- Tempat Lahir --}}
                <x-form.input label="Tempat Lahir" name="tempat_lahir" :value="old('tempat_lahir', $siswa->tempat_lahir)" required />

                {{-- Tanggal Lahir --}}
                {{-- <x-form.datepicker
                    label="Tanggal Lahir"
                    name="tanggal_lahir"
                    :value="old('tanggal_lahir', $siswa->tanggal_lahir)"
                    placeholder="Pilih tanggal"
                    class="datepicker"
                    required /> --}}
                <x-form.datepicker
                    label="Tanggal Lahir"
                    name="tanggal_lahir"
                    :value="old('tanggal_lahir', $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('Y-m-d') : '', )"
                    placeholder="Pilih tanggal"
                    required />

                {{-- Pendidikan Sebelumnya --}}
                <x-form.input label="Pendidikan Sebelumnya" name="pendidikan_sebelumnya" :value="old('pendidikan_sebelumnya', $siswa->pendidikan_sebelumnya)" />

                {{-- Agama --}}
                <x-form.select name="agama" label="Agama" :options="['Islam' => 'Islam', 'Kristen' => 'Kristen', 'Katolik' => 'Katolik', 'Hindu' => 'Hindu', 'Buddha' => 'Buddha', 'Konghucu' => 'Konghucu']" placeholder="Pilih agama" :selected="old('agama', $siswa->agama ?? null)" required />

                {{-- Alamat --}}
                <x-form.textarea label="Alamat" name="alamat" placeholder="Masukkan alamat" rows="4"
                    :value="$siswa->alamat ?? ''" required />

                {{-- No HP --}}
                <x-form.input label="No HP" name="no_hp" :value="old('no_hp', $siswa->no_hp)" placeholder="628xxxxxxxxxx" />

                {{-- Email --}}
                <x-form.input label="Email" name="email" :value="old('email', $siswa->user?->email)" type="email" />


                    <div class="px-5 py-4 sm:px-6 sm:py-5 border-b border-gray-100 dark:border-gray-800">
    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
        Data Kelas pada Tahun Ajaran Aktif
        @if($tahunAktif)
            <span class="ml-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                ({{ $tahunAktif->tahun }} Semester {{ $tahunAktif->semester }})
            </span>
        @endif
    </h3>
</div>

                {{-- Kelas --}}
                <x-form.select
    label="Kelas"
    name="kelas_id"
    :options="$kelasOptions"
    :selected="old('kelas_id', $selectedKelasId)"
    required
/>
                {{-- Status --}}
                <x-form.select
    label="Status"
    name="status"
    :options="['Aktif' => 'Aktif', 'Lulus' => 'Lulus', 'Keluar' => 'Keluar', 'Mutasi' => 'Mutasi']"
    placeholder="Pilih status"
    :selected="old('status', $selectedStatus)"
    :searchable="true"
    required
/>
                <div class="px-5 py-4 sm:px-6 sm:py-5 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Data Wali Murid</h3>
                </div>

                {{-- @php
                    $waliOptions = $wali_list->mapWithKeys(function ($wali) {
                        $label = collect([$wali->nama_ayah, $wali->nama_ibu, $wali->nama_wali])
                            ->filter()
                            ->implode(' - ');
                        return [$wali->id => $label];
                    });

                    $waliOptions['baru'] = '+ Tambah Wali Baru';

                    $selectedWali = old('wali_murid_id', $siswa->wali_murid_id);
                @endphp --}}

                {{-- Pilih Wali --}}
                {{-- <x-form.select
                    label="Pilih Wali"
                    name="wali_murid_id"
                    :options="$waliOptions"
                    :selected="$selectedWali"
                    onchange="toggleWaliForm(this.value)"
                    required
                /> --}}

                {{-- Input Wali Baru --}}
                {{-- Form input wali baru, tampilkan jika pilih 'baru' --}}
                {{-- <div id="form_wali" style="{{ old('wali_murid_id', $selectedWali) == 'baru' ? '' : 'display: none;' }}"> --}}
                <x-form.input label="Nama Ayah" name="nama_ayah" :value="old('nama_ayah', $siswa->nama_ayah)" required />

                <x-form.input label="Nama Ibu" name="nama_ibu" :value="old('nama_ibu', $siswa->nama_ibu)" required />

                <x-form.input label="Nama Wali" name="nama_wali" :value="old('nama_wali', $siswa->nama_wali)" />

                <x-form.input label="No HP" name="no_hp_wali" :value="old('no_hp_wali', $siswa->no_hp_wali)" />
                <x-form.input label="Pekerjaan Ayah" name="pekerjaan_ayah" :value="old('pekerjaan_ayah', $siswa->pekerjaan_ayah)" required />
                <x-form.input label="Pekerjaan Ibu" name="pekerjaan_ibu" :value="old('pekerjaan_ibu', $siswa->pekerjaan_ibu)" required />
                <x-form.input label="Pekerjaan Wali" name="pekerjaan_wali" :value="old('pekerjaan_wali', $siswa->pekerjaan_wali)" />
                <x-form.textarea label="Alamat" name="alamat_wali" rows="4" :value="old('alamat_wali', $siswa->alamat_wali)" />
                {{-- </div> --}}

                {{-- Submit --}}
                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<script>
    function toggleWaliForm(value) {
        const waliForm = document.getElementById('form_wali');
        waliForm.style.display = value === 'baru' ? 'block' : 'none';
    }
</script>
