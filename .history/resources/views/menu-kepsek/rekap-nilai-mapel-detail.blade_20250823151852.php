@php
    $filters = [
        [
            'name' => 'mapel_filter',
            'label' => 'Pilih Mata Pelajaran',
            'options' => $mapelOptions,
            'valueKey' => 'id',
            'labelKey' => 'name',
            'enabled' => true,
            'value' => request('mapel_filter') ?? ($mapelAktif?->id ?? ''),
        ],
    ];
@endphp

<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />
    <div class="mb-2">
        Tahun Semester : {{ $tahunSemesterAktif->tahunAjaran->tahun ?? '-' }} - {{ ucfirst($tahunSemesterAktif->semester ?? '-') }} <br>
        Kelas : {{ $kelas->nama ?? '-' }}<br>
        Wali Kelas : {{ $waliKelas }}
        {{-- Mata Pelajaran : {{ $mapelAktif?->nama ?? '-' }} --}}
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <x-table.toolbar
            :filters="$filters"
            :enable-add-button="false"
            :enable-import="false"
            :enable-export="false"
            :enable-search="false"
            :route="role_route('kepala-sekolah.rekap-nilai-mapel.detail',['kelas_id' => $kelas->id,
                'tahun_semester_filter' => $tahunSemesterAktif->id])"
            >
        </x-table.toolbar>

        <x-table :columns="[
            'nama' => ['label' => 'Nama', 'sortable' => true],
        'uts' => ['label' => 'UTS', 'sortable' => true],
            'uas' => ['label' => 'UAS', 'sortable' => true],
        ]" :data="$nilaiMapel"
            :total-count="$totalCount"
            row-view="menu-kepsek.partials.row-nilai-mapel-detail"
            :selectable="false"
            :actions="[
                'detail' => false,
                'edit' => false,
                'delete' => false,
            ]" />
    </div>
</x-app-layout>
