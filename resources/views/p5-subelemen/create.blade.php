<x-modal name="form-create-p5-subelemen" title="Tambah P5 Sub Elemen" maxWidth="2xl">
    <form action="{{ route('p5-subelemen.store', ['tab' => request('tab', 'subelemen')]) }}" method="POST" enctype="multipart/form-data"
        class="space-y-6 sm:p-6">
        @csrf
        {{-- <input type="hidden" name="tab" value="{{ request('tab', 'subelemen') }}"> --}}
        <input type="hidden" name="tab" value="subelemen">

        {{-- Nama Elemen --}}
        <x-form.select id="p5_elemen_id" name="p5_elemen_id" label="Nama Elemen"
            :options="$elemen->pluck('nama_elemen', 'id')->toArray()"
            placeholder="Pilih Elemen"
            required />

        {{-- Nama Sub Elemen --}}
        <x-form.input id="nama_sub_elemen" name="nama_sub_elemen" label="Nama Sub Elemen" type="text" placeholder="Masukkan nama sub elemen"
            required></x-form.input>

        {{-- Deskripsi Sub Elemen --}}
        <x-form.textarea id="deskripsi_sub_elemen" name="deskripsi_sub_elemen" label="Deskripsi Sub Elemen"
            placeholder="Masukkan deskripsi sub elemen" rows="4" required></x-form.textarea>

        {{-- Tombol Submit --}}
        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                Tambah
            </button>
        </div>
    </form>
</x-modal>
