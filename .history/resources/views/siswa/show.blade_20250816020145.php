<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="'Detail Siswa'" />

    <div class="space-y-6">

        {{-- Card: Data Siswa --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Data Siswa</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700 dark:text-white/90">
                <div><strong>Nama:</strong> {{ $siswa->nama }}</div>
                <div><strong>NIPD:</strong> {{ $siswa->nipd }}</div>
                <div><strong>NISN:</strong> {{ $siswa->nisn }}</div>
                <div><strong>Jenis Kelamin:</strong> {{ $siswa->jenis_kelamin }}</div>
                <div><strong>Tempat Lahir:</strong> {{ $siswa->tempat_lahir }}</div>
                <div><strong>Tanggal Lahir:</strong>
                    {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y') }}</div>
                <div><strong>Agama:</strong> {{ $siswa->agama }}</div>
                <div><strong>Pendidikan Sebelumnya:</strong> {{ $siswa->pendidikan_sebelumnya }}</div>
                <div><strong>Alamat:</strong> {{ $siswa->alamat }}</div>
                <div><strong>No HP:</strong> {{ $siswa->no_hp }}</div>
                <div><strong>Email:</strong> {{ $siswa->email }}</div>
            </div>
        </div>

        {{-- Card: Data Wali --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700  dark:bg-gray-800">
            <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Data Wali Murid</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700 dark:text-white/90">
                <div><strong>Nama Ayah:</strong> {{ $siswa->nama_ayah ?? '-' }}</div>
                <div><strong>Pekerjaan Ayah:</strong> {{ $siswa->pekerjaan_ayah ?? '-' }}</div>
                <div><strong>Nama Ibu:</strong> {{ $siswa->nama_ibu ?? '-' }}</div>
                <div><strong>Pekerjaan Ibu:</strong> {{ $siswa->pekerjaan_ibu ?? '-' }}</div>
                <div><strong>Nama Wali:</strong> {{ $siswa->nama_wali ?? '-' }}</div>
                <div><strong>Pekerjaan Wali:</strong> {{ $siswa->pekerjaan_wali ?? '-' }}</div>
                <div><strong>No HP Wali:</strong> {{ $siswa->no_hp_wali ?? '-' }}</div>
                <div><strong>Alamat Wali:</strong> {{ $siswa->alamat_wali ?? '-' }}</div>
            </div>
        </div>

        {{-- Card: Data Riwayat Siswa --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700  dark:bg-gray-800">
            <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Data Riwayat Siswa</h2>
            {{-- <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700 dark:text-white/90">
                <div><strong>Tahun Masuk:</strong> {{ $siswa->tahun_masuk }}</div>
                <div><strong>Kelas:</strong> {{ $siswa->kelas }}</div>
                <div><strong>Status:</strong> {{ $siswa->status }}</div>
                <div><strong>Catatan:</strong> {{ $siswa->catatan }}</div>
            </div> --}}
            <x-table :columns="[
                'no' => ['label' => 'No', 'sortable' => false],
                'tahun' => ['label' => 'Tahun', 'sortable' => true],
                'semester' => ['label' => 'Semester', 'sortable' => true],
                'kelas' => ['label' => 'Kelas', 'sortable' => true],
                // 'status' => ['label' => 'Status', 'sortable' => true],
                // 'catatan' => ['label' => 'Catatan', 'sortable' => true],
            ]" :data="$riwayatKelas"
                :total-count="$totalCount"
                row-view="siswa.partials.row-detail"
                :selectable="true"
                :actions="[
                    'detail' => false,
                    'edit' => false,
                    'delete' => false,
                ]" />
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-end gap-2">
            <a href="{{ role_route('siswa.edit', ['siswa' => $siswa['id']]) }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-brand-500 rounded-md hover:bg-brand-600">
                Edit Data
            </a>
            <a href="{{ role_route('siswa.index') }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-error-500 rounded-md hover:bg-gray-800">
                Kembali
            </a>
        </div>
    </div>
</x-app-layout>
