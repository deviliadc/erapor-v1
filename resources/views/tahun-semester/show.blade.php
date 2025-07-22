<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <x-table.toolbar
            {{-- :filters="$filters" --}}
            :enable-add-button="false"
            :enable-import="false"
            :enable-export="false"
            :enable-search="true"
            :route="route('tahun-semester.show', $tahunSemester->id)"
        />

        {{-- Table --}}
        <x-table
            :columns="[
                'no' => ['label' => 'No', 'sortable' => false],
                'tahun_semester' => ['label' => 'Tahun', 'sortable' => true],
                'siswa_count' => ['label' => 'Jumlah Siswa', 'sortable' => false],
                'l_count' => ['label' => 'Laki-laki', 'sortable' => false],
                'p_count' => ['label' => 'Perempuan', 'sortable' => false],
            ]"
            :data="$tahun_semester_detail"
            :total-count="$totalCount"
            row-view="tahun-semester.partials.row-detail"
            :selectable="false"
            :actions="[
                'detail' => false,
                'edit' => false,
                'delete' => false,
            ]"
        />
    </div>
</x-app-layout>
