<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    {{-- Edit Presensi Detail --}}
    @include('presensi-detail.edit')

    <h2 class="text-lg font-semibold mb-4 text-gray-700 dark:text-white">Kelas: {{ $presensi->kelas->nama }}</h2>
    {{-- <h3 class="text-md font-medium mb-2 text-gray-600 dark:text-gray-300">Periode: {{ ucfirst($presensi->periode) }}</h3> --}}
    <p class="text-md font-medium mb-2 text-gray-600 dark:text-gray-300">Hari, Tanggal: {{ \Carbon\Carbon::parse($presensi->tanggal)->translatedFormat('l, d F Y') }}</p>
    <p class="text-md font-medium mb-2 text-gray-600 dark:text-gray-300">Catatan: {{ $presensi->catatan ?: '-' }}</p>

    <!-- Wrapper -->
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

        <x-table.toolbar
            :enable-add-button="false"
            :enable-import="false"
            :enable-export="false"
            :enable-search="false"
            :route="role_route('presensi-detail.show', ['presensi_detail' => $presensi->id])"
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
                'edit' => true,
                'delete' => false,
            ]"
            :use-modal-edit="true"
        />
    </div>
</x-app-layout>
