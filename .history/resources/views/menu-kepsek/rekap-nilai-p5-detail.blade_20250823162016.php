{{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\menu-kepsek\rekap-nilai-p5-detail.blade.php --}}
@php
    $filters = [
        // [
        //     'name' => 'tahun_semester_filter',
        //     'label' => 'Pilih Tahun Ajaran & Semester',
        //     'options' => $tahunSemesterSelect,
        //     'valueKey' => 'id',
        //     'labelKey' => 'name',
        //     'enabled' => true,
        //     'value' => request('tahun_semester_filter') ?? $tahunSemesterAktif?->id,
        // ],
        [
            'name' => 'proyek_filter',
            'label' => 'Pilih Proyek',
            'options' => $proyekOptions,
            'valueKey' => 'id',
            'labelKey' => 'name',
            'enabled' => true,
            'value' => request('proyek_filter') ?? ($proyek?->id ?? ''),
        ],
    ];

    $columns = [
        'nama' => ['label' => 'Nama', 'sortable' => false],
    ];
    foreach ($dimensiList as $dimensi) {
        $columns[$dimensi->nama_dimensi] = ['label' => $dimensi->nama_dimensi, 'sortable' => false];
    }
@endphp

<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <div class="mb-2">
        Tahun Semester : {{ $tahunSemesterAktif->tahunAjaran->tahun ?? '-' }} - {{ ucfirst($tahunSemesterAktif->semester ?? '-') }} <br>
        Kelas : {{ $kelas->nama ?? '-' }}<br>
        Wali Kelas : {{ $waliKelas }}
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <x-table.toolbar
            :filters="$filters"
            :enable-add-button="false"
            :enable-import="false"
            :enable-export="false"
            :enable-search="false"
            :route="role_route('kepala-sekolah.nilai-p5.detail', [
                'id' => $kelas->id,
                'tahun_semester_filter' => $tahunSemesterAktif->id
            ])">
        </x-table.toolbar>

        {{-- <table class="min-w-full bg-white rounded shadow">
            <thead>
                <tr>
                    <th>Nama</th>
                    @foreach($dimensiList as $dimensi)
                        <th>{{ $dimensi->nama }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($nilaiP5 as $row)
                <tr>
                    <td>{{ $row['nama'] }}</td>
                    @foreach($dimensiList as $dimensi)
                        <td>{{ $row[$dimensi->nama] ?? '-' }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table> --}}
        <x-table
            :columns="$columns"
            :data="$nilaiP5"
            :total-count="$totalCount"
            row-view="menu-kepsek.partials.row-nilai-p5-detail"
            :row-view-data="['columns' => $columns]"
            :selectable="false"
            :actions="[
                'detail' => false,
                'edit' => false,
                'delete' => false,
            ]"
        />
    </div>
</x-app-layout>
