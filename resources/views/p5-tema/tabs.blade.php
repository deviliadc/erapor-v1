@php
    $activeTab = request('tab', 'tema');
@endphp

{{-- Form Tambah Tema --}}
@include('p5-tema.create')
{{-- Form Edit Tema --}}
@include('p5-tema.edit')

{{-- Toolbar Table --}}
<x-table.toolbar
    :enable-add-button="true"
    :enable-import="false"
    :enable-export="false"
    :enable-search="true"
    searchName="search_tema"
    tabName="tema"
    :route="route('p5.index')">
    <x-slot name="addButton">
        <button type="button"
            onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'form-create-p5-tema' }))"
            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            Tambah
            <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M9.25 5a.75.75 0 011.5 0v4.25H15a.75.75 0 010 1.5h-4.25V15a.75.75 0 01-1.5 0v-4.25H5a.75.75 0 010-1.5h4.25V5z" />
            </svg>
        </button>
    </x-slot>
</x-table.toolbar>

{{-- Table Tema --}}
<x-table :columns="[
    'no' => ['label' => 'No', 'sortable' => false],
    'nama_tema' => ['label' => 'Nama Tema', 'sortable' => false],
    'deskripsi_tema' => ['label' => 'Deskripsi', 'sortable' => false],
]" :data="$tema" :total-count="$temaTotal" row-view="p5-tema.partials.row" :actions="[
    'edit' => true,
    'delete' => true,
    'routes' => [
        'delete' => fn($item) => route('p5-tema.destroy', $item['id']),
    ],
]"
    :use-modal-edit="true" sortPrefix="tema" />
