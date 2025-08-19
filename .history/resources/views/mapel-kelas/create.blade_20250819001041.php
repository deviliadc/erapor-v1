<x-modal name="form-create-mapel-kelas" title="Tambah Mapel" maxWidth="2xl">
    <form action="{{ role_route('mapel-kelas.store') }}" method="POST" enctype="multipart/form-data"
        class="space-y-6 sm:p-6">
        @csrf

        {{-- Mapel --}}
        <x-form.select
            name="mapel_id[]"
            label="Mapel"
            :options="$mapelSelect"
            placeholder="Pilih Mapel"
            :selected="old('mapel_id', [])"
            :searchable="true"
            multiple
            required
        />

        {{-- Guru Pengajar --}}
        <x-form.select
            name="guru_id"
            label="Guru Pengajar"
            :options="$guruSelect"
            placeholder="Pilih Guru Pengajar"
            :selected="old('guru_id')"
            :searchable="true"
            required
        />

        {{-- Tombol Submit --}}
        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                Tambah
            </button>
        </div>
    </form>
</x-modal>
