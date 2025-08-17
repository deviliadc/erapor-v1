<x-modal name="form-create-kelas-mapel" title="Tambah Kelas" maxWidth="2xl">
    <form action="{{ route('kelas-mapel.store') }}" method="POST" enctype="multipart/form-data"
        class="space-y-6 sm:p-6">
        @csrf

        {{-- Kelas --}}
        <x-form.select
            name="kelas_id[]"
            label="Kelas"
            :options="$kelasSelect"
            placeholder="Pilih Kelas"
            :selected="old('kelas_id', [])"
            :searchable="true"
            multiple
            required
        />

        {{-- Guru Wali --}}
        <x-form.select
            name="guru_id"
            label="Guru Wali"
            :options="$guruSelect"
            placeholder="Pilih Guru Wali"
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
