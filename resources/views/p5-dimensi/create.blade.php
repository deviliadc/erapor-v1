<x-modal name="form-create-p5-dimensi" title="Tambah P5 Dimensi" maxWidth="2xl">
    <form action="{{ route('p5-dimensi.store', ['tab' => request('tab', 'dimensi')]) }}" method="POST" enctype="multipart/form-data"
        class="space-y-6 sm:p-6">
        @csrf
        {{-- <input type="hidden" name="tab" value="{{ request('tab', 'dimensi') }}"> --}}
        <input type="hidden" name="tab" value="dimensi">

        {{-- Nama Dumensi --}}
        <x-form.input label="Nama Dimensi"  id="nama_dimensi" name="nama_dimensi" type="text" placeholder="Masukkan nama dimensi"
            required></x-form.input>

        {{-- Deskripsi Dimensi --}}
        <x-form.textarea label="Deskripsi"  id="deskripsi_dimensi" name="deskripsi_dimensi" rows="3"
            placeholder="Masukkan deskripsi dimensi" required></x-form.textarea>

        {{-- Tombol Submit --}}
        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                Tambah
            </button>
        </div>
    </form>
</x-modal>
