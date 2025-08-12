<x-modal name="form-create-kelas" title="Tambah Kelas" maxWidth="2xl">
    <form action="{{ role_route('kelas.store') }}" method="POST" enctype="multipart/form-data"
        class="space-y-6 sm:p-6">
        @csrf

        {{-- Nama Kelas --}}
        <x-form.input label="Nama Kelas" name="nama" :value="old('nama')" required />

        {{-- Fase --}}
        <x-form.select
            label="Fase"
            name="fase_id"
            :options="$faseList"
            :selected="old('fase_id')"
            required />

        {{-- Wali kelas --}}
        {{-- <x-form.select
            name="wali_kelas_id"
            label="Wali Kelas"
            :options="$guru"
            placeholder="Pilih Wali Kelas"
            :selected="old('wali_kelas_id')"
            :searchable="true"
        /> --}}

        {{-- Tombol Submit --}}
        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                Tambah
            </button>
        </div>
    </form>
</x-modal>
