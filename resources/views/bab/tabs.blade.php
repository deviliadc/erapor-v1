@php
    // $activeTab = request('tab', 'bab');
    // $routePrefix = auth()->user()->hasRole('admin') ? 'admin.' : (auth()->user()->hasRole('guru') ? 'guru.' : '');
    $isGuru = auth()->user()->hasRole('guru');
@endphp

{{-- Form Tambah Dimensi --}}
@include('bab.create')
{{-- Form Edit Dimensi --}}
@include('bab.edit')

{{-- Toolbar Table --}}
<x-table.toolbar
    :enable-add-button="!$isGuru"
    :enable-import="false"
    :enable-export="false"
    :enable-search="false"
    tabName="bab"
    :route="role_route('mapel.index')">
    <x-slot name="addButton">
        <button type="button"
            onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'form-create-bab' }))"
            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            Tambah
            <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M9.25 5a.75.75 0 011.5 0v4.25H15a.75.75 0 010 1.5h-4.25V15a.75.75 0 01-1.5 0v-4.25H5a.75.75 0 010-1.5h4.25V5z" />
            </svg>
        </button>
    </x-slot>
</x-table.toolbar>

{{-- Table --}}
<x-table :columns="[
    'no' => ['label' => 'No', 'sortable' => false],
    'nama' => ['label' => 'Nama', 'sortable' => true],
]" :data="$bab"
    :total-count="$babTotal"
    row-view="bab.partials.row"
    :actions="[
        'edit' => !$isGuru,
        'delete' => !$isGuru,
        // 'routes' => [
        //     'delete' => fn($item) => route('bab.destroy', $item['id']),
        // ],
        'routes' => [
            // 'edit' => fn($item) => role_route('bab.edit', ['bab' => $item['id']]),
            // 'detail' => fn($item) => role_route('siswa.show', ['siswa' => $item['id']]),
            'delete' => fn($item) => role_route('bab.destroy', ['bab' => $item['id']]),
        ]
    ]"
    :use-modal-edit="true"
/>
