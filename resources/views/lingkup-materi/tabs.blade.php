{{-- Form Tambah Lingkup Materi --}}
@include('lingkup-materi.create')

{{-- Form Tambah Lingkup Materi --}}
@include('lingkup-materi.edit')

{{-- Toolbar Table --}}
<x-table.toolbar
    {{-- :filters="$filters" --}}
    :enable-add-button="true"
    :enable-import="false"
    :enable-export="false"
    :enable-search="true"
    :route-create="route('mapel.index')">
    <x-slot name="addButton">
        <button type="button"
            onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'form-create-lingkup-materi' }))"
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
    // 'id' => ['label' => 'ID', 'sortable' => true],
    'kelas' => ['label' => 'Kelas', 'sortable' => true],
    'mapel' => ['label' => 'Mapel', 'sortable' => true],
    'bab' => ['label' => 'Bab', 'sortable' => true],
    'nama' => ['label' => 'Lingkup Materi', 'sortable' => true],
    'tujuan_pembelajaran_count' => ['label' => 'Jumlah Tujuan', 'sortable' => false],
    'periode' => ['label' => 'Periode', 'sortable' => true],
    // 'action' => ['label' => 'Aksi', 'sortable' => false],
]"
:data="$lingkupMateri"
:total-count="$lingkupMateriTotal"
row-view="lingkup-materi.partials.row"
:actions="[
    // 'detail' => true,
    'edit' => true,
    'delete' => true,
    'routes' => [
        'delete' => fn($item) => route('lingkup-materi.destroy', $item['id']),
    ],
]"
:use-modal-edit="true"/>
