@foreach ($capaian as $item)
    <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit P5 Capaian" maxWidth="2xl">
        <form action="{{ role_route('p5-capaian.update', ['p5_capaian' => $item['id']], ['tab' => request('tab', 'capaian')]) }}" method="POST"
            class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')
            {{-- <input type="hidden" name="tab" value="{{ request('tab', 'capaian') }}"> --}}
            <input type="hidden" name="tab" value="capaian">

            {{-- Nama Fase --}}
            <x-form.select
                label="Fase"
                name="fase_id"
                :options="$faseList"
                :selected="$item['fase_id'] ?? null"
                required
                placeholder="Pilih fase"
            />

            {{-- Nama Sub Elemen --}}
            <x-form.select
                label="Sub Elemen"
                name="sub_elemen_id"
                :options="$subElemenList"
                :selected="$item['sub_elemen_id'] ?? null"
                required
                placeholder="Pilih sub elemen"
            />

            {{-- Capaian --}}
            <x-form.textarea
                label="Capaian"
                name="capaian"
                rows="3"
                :value="$item['capaian'] ?? null"
                placeholder="Masukkan capaian"
                required
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
