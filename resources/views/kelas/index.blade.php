<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />
    <!-- Wrapper -->
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        {{-- Toolbar Table --}}
        <x-table.toolbar
            {{-- :filters="$filters" --}}
            :enable-add-button="true"
            :enable-import="true"
            :enable-export="true"
            :enable-search="true"
            :route-create="route('kelas.create')"
        />

        {{-- Table --}}
        <x-table :columns="['ID', 'Nama Kelas','Action']" :data="$kelas" :total-count="$totalCount" row-view="siswa.partials.row" />
    </div>

</x-app-layout>
