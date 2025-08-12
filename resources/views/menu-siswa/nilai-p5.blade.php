{{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\menu-siswa\nilai-p5.blade.php --}}
<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />
    <div class="max-w-3xl mx-auto py-8">
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
            <x-table.toolbar
                :enable-add-button="false"
                :enable-import="false"
                :enable-export="false"
                :enable-search="false"
                :route="route('nilai-p5-siswa')">
            </x-table.toolbar>

            <x-table.table
                :columns="[
                    'proyek' => ['label' => 'Proyek', 'sortable' => false],
                    'sub_elemen' => ['label' => 'Sub Elemen', 'sortable' => false],
                    'nilai_akhir' => ['label' => 'Nilai', 'sortable' => false],
                    'predikat' => ['label' => 'Predikat', 'sortable' => false],
                    'tahun_semester' => ['label' => 'Tahun Semester', 'sortable' => false],
                ]"
                :data="$data"
                :paginator="$paginator"
                :selectable="false"
                :actions="['detail' => false, 'edit' => false, 'delete' => false]"
            />
        </div>
    </div>
</x-app-layout>
