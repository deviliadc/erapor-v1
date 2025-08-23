@php
    $filters = [
        [
            'name' => 'tahun_semester_filter',
            'label' => 'Pilih Tahun Ajaran & Semester',
            'options' => $tahunSemesterSelect,
            'valueKey' => 'id',
            'labelKey' => 'name',
            'enabled' => true,
            'value' => request('tahun_semester_filter') ?? $tahunSemesterAktif?->id,
        ],
    ];
@endphp

<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    {{-- Tahun semester aktif --}}
    @if ($tahunSemesterAktif)
        <div class="mb-4 text-base font-semibold text-brand-600">
            Tahun Semester Aktif:
            {{ $tahunSemesterAktif->tahunAjaran->tahun }} - {{ ucfirst($tahunSemesterAktif->semester) }}
        </div>
    @endif
    <!-- Wrapper -->
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        {{-- Toolbar Table --}}
        <x-table.toolbar
            :filters="$filters"
            :enable-add-button="false"
            :enable-import="false"
            :enable-export="false"
            :enable-search="false"
            :route="role_route('rekap-presensi.index')">
        </x-table.toolbar>

        {{-- Table --}}
        <x-table :columns="[
            // 'no' => ['label' => 'No', 'sortable' => false],
            // 'id' => ['label' => 'ID', 'sortable' => true],
            // 'nama' => ['label' => 'Nama Kelas', 'sortable' => true],
            // 'kelas' => ['label' => 'Kelas', 'sortable' => true],
            // 'wali_kelas' => ['label' => 'Wali Kelas', 'sortable' => true],
            // 'jumlah_siswa' => ['label' => 'Jumlah Siswa', 'sortable' => true],
            // 'action' => ['label' => 'Aksi', 'sortable' => false],
            'ekstra'
        ]" :data="$kelas"
            :total-count="$totalCount"
            row-view="menu-kepsek.partials.row-kelas"
            :selectable="false"
            :actions="[
                'detail' => true,
                'edit' => false,
                'delete' => false,
                'routes' => [
                    'detail' => fn($item) => route('kepala-sekolah.nilai-ekstra.detail', [
                    'id' => $item['id'],
                    'tahun_semester_filter' => request('tahun_semester_filter') ?? ($tahunSemesterId ?? $tahunSemesterAktif?->id),
                    ]),
                ],
            ]" />
    </div>
</x-app-layout>
