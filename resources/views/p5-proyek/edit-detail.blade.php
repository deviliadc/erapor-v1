@php
    $elemenJson = json_encode($elemenList);
    $subElemenJson = json_encode($subElemenList);
@endphp

@foreach ($p5_proyek_detail as $item)
<div x-text="JSON.stringify({dimensi, elemen, subElemen})"></div>
    <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit P5 Proyek Detail" maxWidth="2xl">
        <form action="{{ role_route('p5-proyek-detail.update', ['id' => $item['id']]) }}" method="POST"
            class="space-y-6 sm:p-6"
            x-data="formProyekDetail({
                elemenList: {!! $elemenJson !!},
                subElemenList: {!! $subElemenJson !!},
                dimensiInit: '{{ (string) $item['dimensi_id'] }}',
                elemenInit: '{{ (string) $item['elemen_id'] }}',
                subElemenInit: '{{ (string) $item['sub_elemen_id'] }}'
            })"
            x-init="init()"
        >
            @csrf
            @method('PUT')
            <input type="hidden" name="p5_proyek_id" value="{{ $p5_proyek_id }}">

            {{-- Dimensi --}}
            <label class="block text-sm font-medium">Dimensi <span class="text-error-500">*</span></label>
            <select name="dimensi_id" x-model="dimensi" required class="w-full h-11 rounded-lg border px-4 py-2.5">
                <option value="" >Pilih dimensi</option>
                @foreach ($dimensiList as $dimensi)
                    <option value="{{ $dimensi['id'] }}">{{ $dimensi['nama_dimensi'] }}</option>
                @endforeach
            </select>

            {{-- Elemen --}}
            <template x-if="dimensi">
                <select name="elemen_id" x-model="elemen" ...>
                    <template x-for="e in filteredElemen()" :key="e.id">
                        <option :value="e.id" x-text="e.nama_elemen"></option>
                    </template>
                </select>
            </template>

            {{-- Sub Elemen --}}
            <template x-if="elemen">
                <select name="sub_elemen_id" x-model="subElemen" ...>
                    <template x-for="s in filteredSubElemen()" :key="s.id">
                        <option :value="s.id" x-text="s.nama_sub_elemen"></option>
                    </template>
                </select>
            </template>

            <div class="flex justify-end mt-4">
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-lg">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>
@endforeach
