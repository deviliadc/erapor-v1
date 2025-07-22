<x-modal name="form-create-p5-tema" title="Tambah P5 Tema" maxWidth="2xl">
    <form action="{{ route('p5-tema.store', ['tab' => request('tab', 'tema')]) }}" method="POST" enctype="multipart/form-data"
        class="space-y-6 sm:p-6">
        @csrf
        {{-- <input type="hidden" name="tab" value="{{ request('tab', 'tema') }}"> --}}
        <input type="hidden" name="tab" value="tema">

        {{-- Nama Tema --}}
        <x-form.input label="Nama Tema" id="nama_tema" name="nama_tema" type="text" placeholder="Masukkan nama tema"
            required></x-form.input>

        {{-- Deskripsi Tema --}}
        <x-form.textarea label="Deskripsi" id="deskripsi_tema" name="deskripsi_tema" rows="3" placeholder="Masukkan deskripsi tema" required></x-form.textarea>

        {{-- Tombol Submit --}}
        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                Tambah
            </button>
        </div>
    </form>
</x-modal>
