{{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\presensi-harian\index.blade.php --}}
<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <h2 class="text-lg font-semibold mb-4 text-gray-700 dark:text-white">Kelas: {{ $presensi->kelas->nama }}</h2>
    <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">Tanggal: {{ \Carbon\Carbon::parse($presensi->tanggal)->translatedFormat('l, d F Y') }}</p>
    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Catatan: {{ $presensi->catatan ?: '-' }}</p>

    <!-- Wrapper -->
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

        <x-table.toolbar
            :enable-add-button="false"
            :enable-import="true"
            :enable-export="true"
            :enable-search="false"
            :route="route('presensi-detail.show', $presensi->id)"
            {{-- :route-create="route('presensi-detail.edit')" --}}
        />

        <x-table
            :columns="[
                'no_absen' => ['label' => 'No Absen', 'sortable' => true],
                'nama_siswa' => ['label' => 'Nama Siswa', 'sortable' => true],
                'status' => ['label' => 'Status', 'sortable' => true],
                'keterangan' => ['label' => 'Keterangan', 'sortable' => true],
            ]"
            :data="$data"
            :total-count="$data->total()"
            row-view="presensi-detail.partials.row"
            :actions="[
                'detail' => false,
                'edit' => false,
                'delete' => false,
            ]"
        />
    </div>
</x-app-layout>
