@foreach ($subElemen as $item)
    <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit P5 Sub Elemen" maxWidth="2xl">
        <form action="{{ route('p5-subelemen.update', ['p5_subeleman' => $item['id']], ['tab' => request('tab', 'subelemen')]) }}" method="POST"
            class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="tab" value="{{ request('tab', 'subelemen') }}">
            <input type="hidden" name="tab" value="subelemen">

            {{-- Nama Elemen --}}
            <x-form.select
                id="p5_elemen_id"
                name="p5_elemen_id"
                label="Nama Elemen"
                :options="$elemen->pluck('nama_elemen', 'id')->toArray()"
                :selected="$item['p5_elemen_id'] ?? null"
                placeholder="Pilih Elemen"
                required
            />

            {{-- Nama Sub Elemen --}}
            <x-form.input
                label="Nama Sub Elemen"
                name="nama_sub_elemen"
                :value="$item['nama_sub_elemen'] ?? null"
                required
            />
            {{-- Deskripsi Sub Elemen --}}
            <x-form.textarea
                label="Deskripsi Sub Elemen"
                name="deskripsi_sub_elemen"
                placeholder="Masukkan deskripsi"
                rows="4"
                :value="$item['deskripsi_sub_elemen'] ?? null"
            />

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>
@endforeach
