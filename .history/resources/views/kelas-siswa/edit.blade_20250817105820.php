@foreach ($kelasList as $item)
    <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit Kelas" maxWidth="2xl">
        <form action="{{ route('kelas-siswa.update', ['kelas' => $kelas->id, 'siswa' => $item['id']]) }}" method="POST" class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')


            {{-- Kelas --}}
            <x-form.select
                name="kelas_id"
                label="Kelas"
                :options="$kelasOptions"
                placeholder="Pilih Kelas"
                :selected="$item['kelas_id']"
                :searchable="true"
                required
            />

            {{-- Guru Wali --}}
            <x-form.select
                name="guru_id"
                label="Guru Pengajar"
                :options="$guruOptions"
                placeholder="Pilih Guru Pengajar"
                :selected="$item['guru_id']"
                :searchable="true"
                required
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
