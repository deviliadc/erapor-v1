{{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\menu-siswa\nilai-ekstra.blade.php --}}
<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />
    <div class="max-w-3xl mx-auto py-8">

        {{-- Filter Tahun Semester (opsional, aktifkan jika ingin filter) --}}
        {{-- <form method="GET" class="mb-6">
            <label for="tahun_semester_id" class="mr-2 font-semibold dark:text-gray-200">Tahun Semester:</label>
            <select name="tahun_semester_id" id="tahun_semester_id" onchange="this.form.submit()" class="border rounded px-2 py-1 dark:bg-gray-800 dark:text-white dark:border-gray-600">
                @foreach ($daftarTahunSemester as $ts)
                    <option value="{{ $ts->id }}" {{ request('tahun_semester_id', $tahunAktif->id) == $ts->id ? 'selected' : '' }}>
                        {{ $ts->tahun }} - {{ ucfirst($ts->semester) }}
                    </option>
                @endforeach
            </select>
        </form> --}}

        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
            <x-table.toolbar
                :enable-add-button="false"
                :enable-import="false"
                :enable-export="false"
                :enable-search="false"
                :route="route('nilai-ekstra-siswa')">
            </x-table.toolbar>

            <x-table.table :columns="[
                'ekstra' => ['label' => 'Ekstrakurikuler', 'sortable' => false],
                'nilai_akhir' => ['label' => 'Nilai', 'sortable' => false],
                'deskripsi' => ['label' => 'Deskripsi', 'sortable' => false],
                'tahun_semester' => ['label' => 'Tahun Semester', 'sortable' => false],
            ]"
                :data="$data"
                :paginator="$paginator"
                :total-count="$data->count()"
                :selectable="false"
                :actions="[
                    'detail' => false,
                    'edit' => false,
                    'delete' => false
                ]"
            />
        </div>
    </div>
</x-app-layout>
