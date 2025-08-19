@php
    // $routePrefix = auth()->user()->hasRole('admin') ? 'admin.' : (auth()->user()->hasRole('guru') ? 'guru.' : '');
    // $canDelete = auth()->user()->hasRole('admin');
    $isGuru = auth()->user()->hasRole('guru');
    $filters = [
        [
            'name' => 'tahun_ajaran_filter',
            'label' => 'Pilih Tahun Ajaran',
            'options' => $tahunAjaranSelect,
            'valueKey' => 'id',
            'labelKey' => 'name',
            'enabled' => true,
            // Ambil dari request jika ada, jika tidak pakai tahun yang sedang dibuka, jika tidak pakai tahun aktif
            'value' => request('tahun_ajaran_filter') ?? ($tahunAjaranId ?? $tahunAktif?->id),
        ],
    ];
@endphp
<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

{{-- Form Tambah Kelas --}}
@include('mapel-kelas.create')

{{-- Form Tambah Kelas --}}
{{-- @include('kelas-mapel.edit') --}}

{{-- Tahun ajaran aktif --}}
    @if ($tahunAjaranAktif)
        <div class="mb-4 text-base font-semibold text-brand-600 dark:text-white">
            Tahun Ajaran Aktif: {{ $tahunAjaranAktif->tahun }}
        </div>
    @endif

{{-- Toolbar Table --}}
<x-table.toolbar
    :filters="$filters"
    :enable-add-button="false"
    :enable-import="false"
    :enable-export="false"
    :enable-search="true"
    {{-- :route-create="route(role_route('kelas.create'))" --}}
    {{-- :route="route('kelas-mapel.index', ['kelas' => $kelas->id])" --}}
    >
    <x-slot name="addButton">
        <button type="button"
            onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'form-create-kelas-mapel' }))"
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
    // 'nama' => ['label' => 'Nama Kelas', 'sortable' => true],
    'mapel' => ['label' => 'Mapel', 'sortable' => true],
    'guru' => ['label' => 'Guru', 'sortable' => true],
    // 'action' => ['label' => 'Aksi', 'sortable' => false],
]"
:data="$mapelList"
:total-count="$totalCount"
row-view="kelas-mapel.partials.row"
:actions="[
    'edit' => true,
    'delete' => true,
    'routes' => [
        'delete' => fn($item) => route('kelas-mapel.destroy', [
            'kelas' => $kelas->id,
            'mapel' => $item['id'], // ini ID dari GuruKelas
        ]),
        // 'delete' => fn($item) => role_route('kelas-siswa.destroy', [
        //     'kelas_siswa' => $item['id'],
        // ]),
    ],
]"
:use-modal-edit="true"
/>
</x-app-layout>
