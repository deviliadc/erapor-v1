@php
$filters = [
    [
        'name' => 'tahun_semester_id',
        'label' => 'Pilih Tahun Ajaran',
        'options' => $tahun_semester->map(function ($item) {
            $item->label = ($item->tahunAjaran ? $item->tahunAjaran->tahun : '-') . ' - Semester ' . ucfirst($item->semester);
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

    <!-- Wrapper -->
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

        <x-table.toolbar
            :filters="$filters"
            :enable-add-button="true"
            :enable-import="false"
            :enable-export="true"
            
            :enable-search="true"
            :route="role_route('presensi-harian.index')"
            :route-create="role_route('presensi-harian.create')"
        />

        <x-table
            :columns="[
                'tanggal' => ['label' => 'Tanggal', 'sortable' => true],
                'kelas' => ['label' => 'Kelas', 'sortable' => true],
                'catatan' => ['label' => 'Catatan', 'sortable' => false],
            ]"
            :data="$data"
            :total-count="$data->total()"
            row-view="presensi-harian.partials.row"
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
