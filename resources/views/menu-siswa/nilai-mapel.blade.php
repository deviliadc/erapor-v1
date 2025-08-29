{{-- filepath: resources/views/menu-siswa/nilai-mapel.blade.php --}}
<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />
    <div class="max-w-3xl mx-auto py-8">
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
            @php
                $optionsTahunSemester = collect($daftarTahunSemester ?? [])->map(function($ts) {
                    return [
                        'id' => $ts->id,
                        'label' => $ts->tahunAjaran->tahun . ' - ' . ucfirst($ts->semester),
                    ];
                })->values();
                $filters = [
                    [
                        'name' => 'tahun_semester_id',
                        'label' => 'Tahun Semester',
                        'options' => $optionsTahunSemester,
                        'valueKey' => 'id',
                        'labelKey' => 'label',
                        'enabled' => true,
                        'value' => request('tahun_semester_id', $tahunAktif->id),
                    ],
                ];
            @endphp
            <x-table.toolbar
                :filters="$filters"
                :enable-add-button="false"
                :enable-import="false"
                :enable-export="false"
                :enable-search="false"
                :route="route('nilai-mapel-siswa')">
            </x-table.toolbar>

            <x-table.table
                :columns="[
                    'nama' => ['label' => 'Mata Pelajaran', 'sortable' => false],
                    'uts' => ['label' => 'UTS', 'sortable' => false],
                    'uas' => ['label' => 'UAS', 'sortable' => false],
                ]"
                :data="$data"
                :paginator="$paginator"
                :selectable="false"
                :actions="['detail' => false, 'edit' => false, 'delete' => false]"
            />
        </div>
    </div>
</x-app-layout>
