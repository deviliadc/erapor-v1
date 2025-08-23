<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />


    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
        <x-dashboard.card title="Total Siswa Laki-laki" :value="$l_count" icon="users" color="blue-light" />
        <x-dashboard.card title="Total Siswa Perempuan" :value="$p_count" icon="users" color="blue-light" />
        <x-dashboard.card title="Total Siswa" :value="$siswa_count" icon="users" color="blue-light" />
    </div>

    <!-- Chart Perbandingan Laki-laki/Perempuan per Kelas -->
    <div class="mb-8">
        <h3 class="font-semibold mb-2">Perbandingan Laki-laki & Perempuan per Kelas</h3>
        <canvas id="chartGenderKelas" height="120"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('chartGenderKelas').getContext('2d');
            const kelasLabels = @json($kelasGenderChart['labels'] ?? []);
            const lakiData = @json($kelasGenderChart['laki'] ?? []);
            const perempuanData = @json($kelasGenderChart['perempuan'] ?? []);
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: kelasLabels,
                    datasets: [
                        {
                            label: 'Laki-laki',
                            data: lakiData,
                            backgroundColor: 'rgba(59,130,246,0.7)',
                        },
                        {
                            label: 'Perempuan',
                            data: perempuanData,
                            backgroundColor: 'rgba(236,72,153,0.7)',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0, // bulatkan angka
                                stepSize: 1,  // pastikan naik per 1
                            }
                        }
                    }
                }
            });
        });
    </script>

    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <x-table.toolbar
            {{-- :filters="$filters" --}}
            :enable-add-button="false"
            :enable-import="false"
            :enable-export="false"
            :enable-search="true"
            :route="role_route('tahun-ajaran.show', ['tahun_ajaran' => $tahunAjaran->id])"
        />

        {{-- Table --}}
        <x-table
            :columns="[
                'no' => ['label' => 'No', 'sortable' => false],
                'siswa' => ['label' => 'Nama', 'sortable' => true],
                'nipd' => ['label' => 'NIPD', 'sortable' => false],
                'nisn' => ['label' => 'NISN', 'sortable' => false],
                'kelas' => ['label' => 'Kelas', 'sortable' => false],
                'jenis_kelamin' => ['label' => 'L/P', 'sortable' => false],
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
