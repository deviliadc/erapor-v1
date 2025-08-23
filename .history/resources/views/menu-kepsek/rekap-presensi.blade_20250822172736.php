@php
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
            'kelas' => ['label' => 'Kelas', 'sortable' => true],
            'wali_kelas' => ['label' => 'Wali Kelas', 'sortable' => true],
            'jumlah_siswa' => ['label' => 'Jumlah Siswa', 'sortable' => true],
            // 'action' => ['label' => 'Aksi', 'sortable' => false],
        ]" :data="$kelas"
            :total-count="$totalCount"
            row-view="menu-kepsek.partials.row"
            :selectable="false"
            :actions="[
                'detail' => true,
                'edit' => true,
                'delete' => false,
                'routes' => [
                    'detail' => fn($item) => role_route('kelas-siswa.show', [
                        'kelas_siswa' => $item['id'],
                        'tahun_ajaran_filter' => request('tahun_ajaran_filter') ?? ($tahunAjaranId ?? $tahunAjaranAktif?->id),
                    ]),
                    // 'delete' => fn($item) => role_route('kelas-siswa.destroy', [
                    //     'kelas_siswa' => $item['id'],
                    // ]),
                ],
            ]" :use-modal-edit="true" />
    </div>
</x-app-layout>
