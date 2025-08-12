@foreach ($data as $item)
    <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit Presensi Siswa" maxWidth="2xl">
        <form method="POST" action="{{ role_route('presensi-detail.update', ['presensi_detail' => $item['id']]) }}" class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')

            {{-- Nama Siswa (readonly) --}}
            <x-form.input
                name="nama_siswa"
                label="Nama Siswa"
                :value="$item['nama_siswa']"
                readonly
            />

            {{-- Tanggal (readonly) --}}
            <x-form.input
                name="tanggal"
                label="Tanggal"
                :value="isset($presensi) ? \Carbon\Carbon::parse($presensi->tanggal)->translatedFormat('l, d F Y') : '-'"
                readonly
            />

            {{-- Status Kehadiran --}}
            <x-form.select
                name="status"
                label="Status Kehadiran"
                :options="['Hadir' => 'Hadir', 'Izin' => 'Izin', 'Sakit' => 'Sakit', 'Alpha' => 'Alpha']"
                :selected="$item['status']"
                required
            />

            {{-- Keterangan --}}
            <x-form.input
                name="keterangan"
                label="Keterangan"
                :value="old('keterangan', $item['keterangan'])"
            />

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>
@endforeach
