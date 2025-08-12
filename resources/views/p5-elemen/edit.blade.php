@foreach ($elemen as $item)
    <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit P5 Elemen" maxWidth="2xl">
        <form action="{{ role_route('p5-elemen.update', ['p5_eleman' => $item['id']], ['tab' => request('tab', 'elemen')]) }}" method="POST"
            class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')
            {{-- <input type="hidden" name="tab" value="{{ request('tab', 'elemen') }}"> --}}
            <input type="hidden" name="tab" value="elemen">

            {{-- Nama Dimensi --}}
            <x-form.select
                id="p5_dimensi_id"
                name="p5_dimensi_id"
                label="Nama Dimensi"
                :options="$dimensi->pluck('nama_dimensi', 'id')->toArray()"
                :selected="old('p5_dimensi_id', $item['p5_dimensi_id'] ?? null)"
                placeholder="Pilih Dimensi"
                required
            />

            {{-- Nama Elemen --}}
            <x-form.input
                label="Nama Elemen"
                name="nama_elemen"
                placeholder="Masukkan nama elemen"
                :value="old('nama_elemen', $item['nama_elemen'])"
                required/>

            {{-- Deskripsi Elemen --}}
            {{-- <x-form.textarea
                label="Deskripsi Elemen"
                name="deskripsi_elemen"
                placeholder="Masukkan deskripsi"
                rows="4"
                :value="old('deskripsi_elemen', $item['deskripsi_elemen'])" /> --}}

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>
@endforeach
