<x-modal name="form-create-bab" title="Tambah Bab" maxWidth="2xl">
    <form action="{{ route('bab.store', ['tab' => request('tab', 'bab')]) }}" method="POST" enctype="multipart/form-data"
        class="space-y-6 sm:p-6">
        @csrf
        {{-- <input type="hidden" name="tab" value="{{ request('tab', 'dimensi') }}"> --}}
        <input type="hidden" name="tab" value="bab">

        {{-- Nama Bab --}}
        <x-form.input
            label="Nama"
            id="nama"
            name="nama"
            type="text"
            placeholder="Masukkan nama bab"
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
