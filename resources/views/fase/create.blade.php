<x-modal name="form-create-fase" title="Tambah Fase" maxWidth="2xl">
    <form action="{{ role_route('fase.store') }}" method="POST" enctype="multipart/form-data"
        class="space-y-6 sm:p-6">
        @csrf

        {{-- Nama --}}
        <x-form.input
            label="Nama Fase"
            name="nama"
            :value="old('nama')"
            required />

        {{-- Keterangan --}}
        <x-form.textarea
            label="Keterangan"
            name="keterangan"
            :value="old('keterangan')"
            placeholder="Masukkan keterangan"
            rows="3" />

        {{-- Tombol Submit --}}
        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                Tambah
            </button>
        </div>
    </form>
</x-modal>
