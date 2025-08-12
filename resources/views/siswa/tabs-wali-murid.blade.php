
    {{-- <div class="space-y-6"> --}}

        {{-- Card: Data Wali --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700  dark:bg-gray-800">
            <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Data Wali Murid</h2>
            {{-- @if ($siswa->wali)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700 dark:text-white/90">
                    <div><strong>Nama Ayah:</strong> {{ $siswa->wali->nama_ayah }}</div>
                    <div><strong>Pekerjaan Ayah:</strong> {{ $siswa->wali->pekerjaan_ayah }}</div>
                    <div><strong>Nama Ibu:</strong> {{ $siswa->wali->nama_ibu }}</div>
                    <div><strong>Pekerjaan Ibu:</strong> {{ $siswa->wali->pekerjaan_ibu }}</div>
                    <div><strong>Nama Wali:</strong> {{ $siswa->wali->nama_wali ?? '-'}}</div>
                    <div><strong>Pekerjaan Wali:</strong> {{ $siswa->wali->pekerjaan_wali ?? '-'}}</div>
                    <div><strong>No HP Wali:</strong> {{ $siswa->wali->no_hp ?? '-'}}</div>
                    <div><strong>Alamat Wali:</strong> {{ $siswa->wali->alamat }}</div>
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada data wali murid.</p>
            @endif --}}
                        @if ($data)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700 dark:text-white/90">
                    <div><strong>Nama Ayah:</strong> {{ $data->nama_ayah }}</div>
                    <div><strong>Pekerjaan Ayah:</strong> {{ $data->pekerjaan_ayah }}</div>
                    <div><strong>Nama Ibu:</strong> {{ $data->nama_ibu }}</div>
                    <div><strong>Pekerjaan Ibu:</strong> {{ $data->pekerjaan_ibu }}</div>
                    <div><strong>Nama Wali:</strong> {{ $data->nama_wali ?? '-' }}</div>
                    <div><strong>Pekerjaan Wali:</strong> {{ $data->pekerjaan_wali ?? '-' }}</div>
                    <div><strong>No HP Wali:</strong> {{ $data->no_hp ?? '-' }}</div>
                    <div><strong>Alamat Wali:</strong> {{ $data->alamat }}</div>
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada data wali murid.</p>
            @endif
        </div>

        {{-- Tombol Aksi --}}
        {{-- <div class="flex justify-end gap-2">
            <a href="{{ route('siswa.edit', $siswa->id) }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-brand-500 rounded-md hover:bg-brand-600">
                Edit Data
            </a>
            <a href="{{ route('siswa.index') }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-error-500 rounded-md hover:bg-gray-800">
                Kembali
            </a>
        </div>
    </div> --}}
