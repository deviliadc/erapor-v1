@foreach ($siswa as $item)
    <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit Nomor Absen" maxWidth="2xl">
        <form action="{{ role_route('kelas.siswa.update', ['kelas' => $kelas->id, 'siswa' => $item['id']]) }}" method="POST" class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunSemesterId }}">

            {{-- Siswa (readonly) --}}
            <x-form.input
                name="nama_siswa"
                label="Siswa"
                :value="$item['nama']"
                readonly
                class="cursor-not-allowed bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400"
            />

            {{-- No Absen --}}
            <x-form.input
                name="no_absen"
                label="No. Absen"
                type="number"
                :value="($item['no_absen'] !== '-' ? $item['no_absen'] : '')"
                placeholder="Masukkan No. Absen"
                required
                min="1"
                max="999"
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
