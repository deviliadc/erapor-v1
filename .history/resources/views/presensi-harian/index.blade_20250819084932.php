@php
$filters = [
    [
        'name' => 'tahun_semester_id',
        'label' => 'Pilih Tahun Ajaran',
        'options' => $tahunSemester->map(function ($item) use ($tahunSemesterAktif) {
            $label = ($item->tahunAjaran ? $item->tahunAjaran->tahun : '-') . ' - Semester ' . ucfirst($item->semester);
            if ($tahunSemesterAktif && $item->id == $tahunSemesterAktif->id) {
                $label .= ' (Aktif)';
            }
            $item->label = $label;
            return $item;
        }),
        'valueKey' => 'id',
        'labelKey' => 'label',
        'enabled' => true,
    ],
];
@endphp

<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    {{-- Edit Catatan --}}
    @include('presensi-harian.edit')

    {{-- Export Modal --}}
    @include('presensi-harian.export')

    {{-- Import Modal --}}
    @include('presensi-harian.import')

    {{-- Tahun Aktif --}}
    <div class="p-4">
        <h3 class="text-lg font-semibold">Tahun Aktif</h3>
        <p>{{ $tahunAjaranAktif->tahun ?? '-' }} - Semester {{ ucfirst($tahunSemesterAktif->semester ?? '-') }}</p>
    </div>

    <!-- Wrapper -->
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

        <x-table.toolbar
            :filters="$filters"
            :enable-add-button="true"
            :enable-import="true"
            :importModalName="'import-presensi-harian'"
            :enable-export="true"
            :exportModalName="'export-presensi-harian'"
            :enable-search="true"
            {{-- :route="role_route('presensi-harian.index')" --}}
            :route-create="role_route('presensi-harian.create')"
            :routeExport="role_route('presensi-harian.export')"
            filename="data_presensi_harian_{{ now()->format('Ymd_His') }}"
        />

        <x-table
            :columns="[
                'tanggal' => ['label' => 'Tanggal', 'sortable' => true],
                'kelas' => ['label' => 'Kelas', 'sortable' => true],
                'catatan' => ['label' => 'Catatan', 'sortable' => false],
            ]"
            :data="$paginator"
            :total-count="$totalCount"
            row-view="presensi-harian.partials.row"
            :selectable="false"
            :actions="[
                'detail' => true,
                'edit' => true,
                'delete' => true,
                'routes' => [
                    'detail' => fn($item) => role_route('presensi-detail.show', ['presensi_detail' => $item['id']]),
                    'delete' => fn($item) => role_route('presensi-harian.destroy', ['presensi_harian' => $item['id']]),
                ]
            ]"
            :use-modal-edit="true"
        />
    </div>
</x-app-layout>
