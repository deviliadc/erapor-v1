@php
    // $activeTab = request('tab', 'capaian');
    $isGuru = auth()->user()->hasRole('guru');
@endphp

{{-- Form Tambah Sub Elemen --}}
@include('p5-capaian.create')
{{-- Form Edit Sub Elemen --}}
@include('p5-capaian.edit')

{{-- Toolbar Table --}}
<x-table.toolbar
    :enable-add-button="!$isGuru"
    :enable-import="false"
    :enable-export="false"
    :enable-search="true"
    searchName="search_capaian"
    tabName="capaian"
    :route="role_route('p5.index')">
    <x-slot name="addButton">
        <button type="button"
            onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'form-create-p5-capaian' }))"
            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            Tambah
            <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M9.25 5a.75.75 0 011.5 0v4.25H15a.75.75 0 010 1.5h-4.25V15a.75.75 0 01-1.5 0v-4.25H5a.75.75 0 010-1.5h4.25V5z" />
            </svg>
        </button>
    </x-slot>
</x-table.toolbar>

{{-- Table Sub Elemen --}}
<x-table :columns="[
    'no' => ['label' => 'No', 'sortable' => false],
    'fase' => ['label' => 'Fase', 'sortable' => true],
    'nama_sub_elemen' => ['label' => 'Nama Sub Elemen', 'sortable' => true],
    'capaian' => ['label' => 'Capaian', 'sortable' => true],
]"
    :data="$capaian"
    :total-count="$capaianTotal"
    row-view="p5-capaian.partials.row"
    :actions="[
        'edit' => !$isGuru,
        'delete' => !$isGuru,
        'routes' => [
            'delete' => fn($item) => role_route('p5-capaian.destroy', ['p5_capaian' => $item['id']]),
    ],
]"
    :use-modal-edit="true" />
