<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <!-- Wrapper -->
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

        {{-- Toolbar Table --}}
        <x-table.toolbar
            :enable-add-button="true"
            :enable-import="true"
            :enable-export="true"
            :enable-search="true"
            :route="route('siswa.index')"
            :route-create="route('siswa.create')"
        />

        {{-- Table --}}
        <x-table :columns="[
            'no' => ['label' => 'No', 'sortable' => false], // <- nomor urut biasa, tidak perlu di-sort
            // 'id' => ['label' => 'ID', 'sortable' => true],
            'name' => ['label' => 'Nama', 'sortable' => true],
            'nis' => ['label' => 'NIS', 'sortable' => true],
            'nisn' => ['label' => 'NISN', 'sortable' => true],
            'nama_ayah' => ['label' => 'Nama Ayah', 'sortable' => true],
            'nama_ibu' => ['label' => 'Nama Ibu', 'sortable' => true],
            'nama_wali' => ['label' => 'Nama Wali', 'sortable' => true],
            'jenis_kelamin' => ['label' => 'Jenis Kelamin', 'sortable' => true],
            'tempat_lahir' => ['label' => 'Tempat Lahir', 'sortable' => true],
            'tanggal_lahir' => ['label' => 'Tanggal Lahir', 'sortable' => true],
            'pendidikan_sebelumnya' => ['label' => 'Pendidikan Sebelumnya', 'sortable' => true],
            'alamat' => ['label' => 'Alamat', 'sortable' => true],
            'no_hp' => ['label' => 'No HP', 'sortable' => true],
            'no_hp_wali' => ['label' => 'No HP Wali', 'sortable' => true],
            'email' => ['label' => 'Email', 'sortable' => true],
            'status' => ['label' => 'Status', 'sortable' => true],
        ]"
        :data="$siswa"
        :total-count="$totalCount"
        row-view="siswa.partials.row"
        :actions="[
            'detail' => true,
            'edit' => true,
            'delete' => true,
            'routes' => [
                'edit' => fn($item) => route('siswa.edit', ['siswa' => $item['id']]),
                'detail' => fn($item) => route('siswa.show', ['siswa' => $item['id']]),
            ]
        ]"/>
    </div>
</x-app-layout>
