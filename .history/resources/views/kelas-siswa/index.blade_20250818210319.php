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
    {{-- @include('kelas-siswa.create') --}}

    {{-- Form Tambah Kelas --}}
    @include('kelas-siswa.edit-wali')

    {{-- Tahun ajaran aktif --}}
    @if ($tahunAjaranAktif)
        <div class="mb-4 text-base font-semibold text-brand-600">
            Tahun Ajaran Aktif: {{ $tahunAjaranAktif->tahun }}
        </div>
    @endif
    <!-- Wrapper -->
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        {{-- Toolbar Table --}}
        <x-table.toolbar
            :filters="$filters"
            :enable-add-button="true"
            :enable-import="false"
            :enable-export="false"
            :enable-search="false"
            :route="role_route('kelas-siswa.index')">
            <x-slot name="addButton">
                {{-- <button type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'form-create-kelas-siswa' }))"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Tambah
                    <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M9.25 5a.75.75 0 011.5 0v4.25H15a.75.75 0 010 1.5h-4.25V15a.75.75 0 01-1.5 0v-4.25H5a.75.75 0 010-1.5h4.25V5z" />
                    </svg>
                </button> --}}
                {{-- Tombol Promosi --}}
                <button type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'form-promote-siswa' }))"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.493 4.493 0 0 0-1.757 4.306 4.493 4.493 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                    </svg>
                    Naik Kelas
                </button>
            </x-slot>
        </x-table.toolbar>

        {{-- Table --}}
        <x-table :columns="[
            // 'no' => ['label' => 'No', 'sortable' => false],
            // 'id' => ['label' => 'ID', 'sortable' => true],
            // 'nama' => ['label' => 'Nama Kelas', 'sortable' => true],
            'kelas' => ['label' => 'Kelas', 'sortable' => true],
            'wali_kelas' => ['label' => 'Wali Kelas', 'sortable' => true],
            'jumlah_siswa' => ['label' => 'Jumlah Siswa', 'sortable' => true],
            // 'action' => ['label' => 'Aksi', 'sortable' => false],
        ]" :data="$data" 
            :total-count="$totalCount" 
            row-view="kelas-siswa.partials.row"
            :selectable="false" 
            :actions="[
                'detail' => true,
                'edit' => true,
                'delete' => true,
                'routes' => [
                    'detail' => fn($item) => role_route('kelas-siswa.show', [
                        'kelas_siswa' => $item['id'],
                        'tahun_ajaran_filter' => request('tahun_ajaran_filter') ?? ($tahunAjaranId ?? $tahunAjaranAktif?->id),
                    ]),
                    'delete' => fn($item) => role_route('kelas-siswa.destroy', [
                        'kelas_siswa' => $item['id'],
                    ]),
                ],
            ]" :use-modal-edit="true" />
    </div>
</x-app-layout>
