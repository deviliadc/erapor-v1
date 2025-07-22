@foreach ($mapel as $item)
    <x-modal name="edit-modal-{{ $item->id }}" title="Edit Mata Pelajaran" maxWidth="2xl">
        <form action="{{ route('mapel.update', $item->id) }}" method="POST"
            class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')

            {{-- Kode Mapel --}}
            <x-form.input label="Kode Mapel" name="kode_mapel" :value="old('kode_mapel', $item->kode_mapel)" required />

            {{-- Nama --}}
            <x-form.input label="Nama" name="nama" :value="old('nama', $item->nama)" required />

            {{-- Kategori --}}
            <x-form.select name="kategori" label="Kategori" :options="['Wajib' => 'Wajib', 'Muatan Lokal' => 'Muatan Lokal']" :selected="old('kategori', $item->kategori)"
                placeholder="Pilih kategori" required />

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>
@endforeach
