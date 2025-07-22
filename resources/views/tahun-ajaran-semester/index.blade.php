<x-app-layout>
    <x-breadcrumb title="Manage tahun_semester" />
    <!-- Wrapper -->
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        {{-- Toolbar Table --}}
        <x-table.toolbar
            {{-- :filters="$filters" --}}
            :enable-add-button="true"
            :enable-import="false"
            :enable-export="false"
            :enable-search="true"
            :route-create="route('admin.tahun_semester.create')"
        />

        {{-- Table --}}
        <x-table :columns="['ID', 'Name', 'NIP', 'Email', 'No HP', 'Alamat', 'Jenis Kelamin', 'Action']" :data="$users" :total-count="$totalCount" row-view="admin.tahun_semester.partials.row" />
    </div>

</x-app-layout>
