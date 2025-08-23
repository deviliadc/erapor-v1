<x-app-layout>
 <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <div class="mb-2">
        Tahun Semester :
        {{ $tahunSemesterAktif->tahunAjaran->tahun ?? '-' }} - {{ ucfirst($tahunSemesterAktif->semester ?? '-') }} <br>
        Kelas : {{ $kelas->nama ?? '-' }}<br>
        Wali Kelas : {{ $waliKelas }}
    </div>
     <!-- Wrapper -->
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

    <x-table :columns="[
            // 'no' => ['label' => 'No', 'sortable' => false],
            // 'id' => ['label' => 'ID', 'sortable' => true],
            // 'nama' => ['label' => 'Nama Kelas', 'sortable' => true],
            'nama' => ['label' => 'Nama', 'sortable' => true],
            'Nilai' => ['label' => 'Nilai', 'sortable' => true],
            'deskripsi' => ['label' => 'Deskripsi', 'sortable' => true],
            // 'action' => ['label' => 'Aksi', 'sortable' => false],
        ]" :data="$nilaiEkstra"
            :total-count="$totalCount"
            row-view="menu-kepsek.partials.row-nilai-ekstra-detail"
            {{-- :row-view-data="['nilaiEkstra' => $nilaiEkstra]" --}}
            :selectable="false"
            :actions="[
                'detail' => false,
                'edit' => false,
                'delete' => false,
            ]" />
    </div>
</x-app-layout>
