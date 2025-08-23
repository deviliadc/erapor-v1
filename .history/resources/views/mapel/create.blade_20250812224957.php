<x-modal name="form-create-mapel" title="Tambah Mata Pelajaran" maxWidth="2xl">
    <form action="{{ role_route('mapel.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 sm:p-6">
        @csrf

        {{-- Kode Mapel --}}
        <x-form.input label="Kode Mapel" name="kode_mapel" :value="old('kode_mapel')" required />

        {{-- Nama --}}
        <x-form.input label="Nama" name="nama" :value="old('nama')" required />

        {{-- Kategori --}}
        <x-form.select name="kategori" label="Kategori" :options="['Wajib' => 'Wajib', 'Muatan Lokal' => 'Muatan Lokal']" placeholder="Pilih kategori" required />

        {{-- Agama --}}
        <x-form.select name="agama" label="Agama" :options="[
            '' => 'Pilih agama',
            'Islam' => 'Islam',
            'Kristen' => 'Kristen',
            'Katolik' => 'Katolik',
            'Hindu' => 'Hindu',
            'Buddha' => 'Buddha',
            'Konghucu' => 'Konghucu',
        ]" placeholder="Pilih agama" />

        {{-- Tombol Submit --}}
        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                Tambah
            </button>
        </div>
    </form>
</x-modal>
