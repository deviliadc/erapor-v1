@php
    $elemenJson = json_encode($elemenList);
    $subElemenJson = json_encode($subElemenList);
@endphp

<x-modal name="form-create-proyek-detail" title="Tambah P5 Proyek Detail" maxWidth="2xl">
    <form action="{{ role_route('p5-proyek-detail.store') }}" method="POST"
        enctype="multipart/form-data" class="space-y-6 sm:p-6"
        x-data="formProyekDetail()"
        x-init="init({ elemenList: {{ $elemenJson }}, subElemenList: {{ $subElemenJson }} })">
        @csrf
        <input type="hidden" name="p5_proyek_id" value="{{ request('p5_proyek') ?? $proyek->id }}">

        {{-- Dimensi --}}
        <label class="block text-sm font-medium">Dimensi <span class="text-error-500">*</span></label>
        <select name="dimensi_id" x-model="dimensi" required class="w-full h-11 rounded-lg border px-4 py-2.5">
            <option value="" disabled selected>Pilih dimensi</option>
            @foreach($dimensiList as $dimensi)
                <option value="{{ $dimensi['id'] }}">{{ $dimensi['nama_dimensi'] }}</option>
            @endforeach
        </select>

        {{-- Elemen --}}
        <template x-if="dimensi">
            <div>
                <label class="block text-sm font-medium">Elemen <span class="text-error-500">*</span></label>
                <select name="elemen_id" x-model="elemen" required class="w-full h-11 rounded-lg border px-4 py-2.5">
                    <option value="" disabled selected>Pilih elemen</option>
                    <template x-for="e in filteredElemen()" :key="e.id">
                        <option :value="e.id" x-text="e.nama_elemen"></option>
                    </template>
                </select>
            </div>
        </template>

        {{-- Sub Elemen --}}
        <template x-if="elemen">
            <div>
                <label class="block text-sm font-medium">Sub Elemen <span class="text-error-500">*</span></label>
                <select name="sub_elemen_id" x-model="subElemen" required class="w-full h-11 rounded-lg border px-4 py-2.5">
                    <option value="" disabled selected>Pilih sub elemen</option>
                    <template x-for="s in filteredSubElemen()" :key="s.id">
                        <option :value="s.id" x-text="s.nama_sub_elemen"></option>
                    </template>
                </select>
            </div>
        </template>

        <div class="flex justify-end mt-4">
            <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-lg">
                Tambah
            </button>
        </div>
    </form>
</x-modal>
