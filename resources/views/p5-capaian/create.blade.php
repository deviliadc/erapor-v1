<x-modal name="form-create-p5-capaian" title="Tambah P5 Capaian" maxWidth="2xl">
    <form action="{{ role_route('p5-capaian.store', ['tab' => request('tab', 'capaian')]) }}" method="POST" enctype="multipart/form-data"
        class="space-y-6 sm:p-6">
        @csrf
        {{-- <input type="hidden" name="tab" value="{{ request('tab', 'capaian') }}"> --}}
        <input type="hidden" name="tab" value="capaian">

        {{-- Nama Fase --}}
        <x-form.select
            id="fase_id"
            name="fase_id"
            label="Fase"
            :options="$faseList"
            required
            placeholder="Pilih fase"/>

        {{-- Nama Sub Elemen --}}
        <x-form.select
            id="sub_elemen_id"
            name="sub_elemen_id"
            label="Sub Elemen"
            :options="$subElemenList"
            required
            placeholder="Pilih sub elemen"/>

        {{-- Capaian --}}
        <x-form.textarea
            id="capaian"
            name="capaian"
            label="Capaian"
            rows="3"
            placeholder="Masukkan capaian"
            required></x-form.textarea>

        {{-- Tombol Submit --}}
        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                Tambah
            </button>
        </div>
    </form>
</x-modal>
