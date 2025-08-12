<x-modal name="form-create-p5-elemen" title="Tambah P5 Elemen" maxWidth="2xl">
    <form action="{{ role_route('p5-elemen.store', ['tab' => request('tab', 'elemen')]) }}" method="POST" enctype="multipart/form-data"
        class="space-y-6 sm:p-6">
        @csrf
        {{-- <input type="hidden" name="tab" value="{{ request('tab', 'elemen') }}"> --}}
        <input type="hidden" name="tab" value="elemen">

        {{-- Nama Dimensi --}}
        <x-form.select
            id="p5_dimensi_id"
            name="p5_dimensi_id"
            label="Nama Dimensi"
            :options="$dimensi->pluck('nama_dimensi', 'id')->toArray()"
            placeholder="Pilih Dimensi"
            required />

        {{-- Nama Elemen --}}
        <x-form.input
            id="nama_elemen"
            name="nama_elemen"
            label="Nama Elemen"
            type="text"
            placeholder="Masukkan nama elemen"
            required/>

        {{-- Deskripsi Elemen --}}
        {{-- <x-form.textarea
            id="deskripsi_elemen"
            name="deskripsi_elemen"
            label="Deskripsi Elemen"
            placeholder="Masukkan deskripsi elemen"
            rows="4"
            required></x-form.textarea> --}}

        {{-- Tombol Submit --}}
        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                Tambah
            </button>
        </div>
    </form>
</x-modal>
