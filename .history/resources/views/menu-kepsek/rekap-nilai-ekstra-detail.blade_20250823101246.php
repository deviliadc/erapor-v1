@php
    $filters = [
        [
            'name' => 'ekstra_filter',
            'label' => 'Pilih Ekstrakurikuler',
            'options' => $ekstraOptions,
            'valueKey' => 'id',
            'labelKey' => 'name',
            'enabled' => true,
            'value' => request('ekstra_filter') ?? $ekstraAktif?->id,
        ],
    ];
@endphp

<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />
    <div class="mb-2">
        Tahun Semester : {{ $tahunSemesterAktif->tahunAjaran->tahun ?? '-' }} - {{ ucfirst($tahunSemesterAktif->semester ?? '-') }} <br>
        Kelas : {{ $kelas->nama ?? '-' }}<br>
        Wali Kelas : {{ $waliKelas }}
        Ekstrakurikuler : {{ $ekstraAktif?->name ?? '-' }}
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <x-table.toolbar
            :filters="$filters"
            :enable-add-button="false"
            :enable-import="false"
            :enable-export="false"
            :enable-search="false"
            {{-- :route="role_route('kepala-sekolah.nilai-ekstra.detail', ['kelas_id' => $kelas->id, 'tahun_semester_filter' => $tahunSemesterAktif->id])"> --}}
        </x-table.toolbar>

        <x-table :columns="[
            'nama' => ['label' => 'Nama', 'sortable' => true],
            'Nilai' => ['label' => 'Nilai', 'sortable' => true],
            'deskripsi' => ['label' => 'Deskripsi', 'sortable' => true],
        ]" :data="$nilaiEkstra"
            :total-count="$totalCount"
            row-view="menu-kepsek.partials.row-nilai-ekstra-detail"
            :selectable="false"
            :actions="[
                'detail' => false,
                'edit' => false,
                'delete' => false,
            ]" />
    </div>
</x-app-layout>
