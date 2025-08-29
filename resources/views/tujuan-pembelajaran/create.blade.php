<x-modal name="form-create-tujuan" title="Tambah Tujuan Pembelajaran" maxWidth="2xl">
    <form action="{{ role_route('tujuan-pembelajaran.store') }}" method="POST" enctype="multipart/form-data"
        class="space-y-6 sm:p-6">
        @csrf
        <input type="hidden" name="tab" value="tujuan-pembelajaran">
        {{-- Pilih Lingkup Materi --}}
        <x-form.select
            label="Lingkup Materi"
            name="lingkup_materi_id"
            :options="$lingkupMateriOptions"
            :selected="old('lingkup_materi_id')"
            required
            placeholder="-- Pilih Lingkup Materi --"
        />
        {{-- Subbab --}}
        <x-form.input
            label="Subbab"
            name="subbab"
            :value="old('subbab')"
            required
            placeholder="Masukkan subbab. Contoh: '1.1.1'"
        />
        {{-- Tujuan Pembelajaran --}}
        <x-form.textarea
            label="Tujuan Pembelajaran"
            name="tujuan"
            :value="old('tujuan')"
            required
            placeholder="Masukkan tujuan pembelajaran"
            rows="3"
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
