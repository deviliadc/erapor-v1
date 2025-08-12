{{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\menu-siswa\absensi.blade.php --}}
<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />
    <div class="max-w-3xl mx-auto py-8">

        {{-- Filter Tahun Semester --}}
        <form method="GET" class="mb-6">
            <label for="tahun_semester_id" class="mr-2 font-semibold dark:text-gray-200">Tahun Semester:</label>
            <select name="tahun_semester_id" id="tahun_semester_id" onchange="this.form.submit()" class="border rounded px-2 py-1 dark:bg-gray-800 dark:text-white dark:border-gray-600">
                @foreach($daftarTahunSemester as $ts)
                    <option value="{{ $ts->id }}" {{ request('tahun_semester_id', $tahunAktif->id) == $ts->id ? 'selected' : '' }}>
                        {{ $ts->tahun }} - {{ ucfirst($ts->semester) }}
                    </option>
                @endforeach
            </select>
        </form>

        {{-- Chart Pie & Line --}}
        <div class="flex flex-row gap-4 mb-8 justify-center items-start">
            <div class="w-1/3 max-w-xs bg-white dark:bg-gray-900 border dark:border-gray-700 rounded-lg shadow p-4 flex items-center justify-center">
                <canvas id="pieAbsensi" width="180" height="180" style="max-width:150px;max-height:150px;"></canvas>
            </div>
            <div class="w-2/3 min-w-[220px] bg-white dark:bg-gray-900 border dark:border-gray-700 rounded-lg shadow p-4 flex items-center justify-center">
                <canvas id="linePresensi" width="400" height="180" style="max-width:100%;max-height:180px;"></canvas>
            </div>
        </div>

        {{-- Tabel detail presensi harian --}}
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03] mb-8">
            <x-table.toolbar
                :enable-add-button="false"
                :enable-import="false"
                :enable-export="false"
                :enable-search="false"
                :route="role_route('absensi-siswa')">
            </x-table.toolbar>
            <x-table.table
                :columns="[
                    'tanggal' => ['label' => 'Tanggal', 'sortable' => false],
                    'status' => ['label' => 'Status', 'sortable' => false],
                    'keterangan' => ['label' => 'Keterangan', 'sortable' => false],
                ]"
                :data="$data"
                :paginator="$paginator"
                :selectable="false"
                :actions="[
                    'detail' => false,
                    'edit' => false,
                    'delete' => false
                    ]"
            />
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Pie Chart Data
        const pieData = {
            labels: ['Sakit', 'Izin', 'Alfa'],
            datasets: [{
                data: [
                    {{ $rekapAbsensi->total_sakit ?? 0 }},
                    {{ $rekapAbsensi->total_izin ?? 0 }},
                    {{ $rekapAbsensi->total_alfa ?? 0 }}
                ],
                backgroundColor: ['#fbbf24', '#60a5fa', '#ef4444'],
                borderColor: ['#d97706', '#2563eb', '#b91c1c'],
                borderWidth: 2
            }]
        };
        new Chart(document.getElementById('pieAbsensi'), {
            type: 'pie',
            data: pieData,
            options: {
                plugins: {
                    legend: { labels: { color: document.documentElement.classList.contains('dark') ? '#fff' : '#000' } }
                }
            }
        });

        // Line Chart Data
        const lineChartData = {
            labels: {!! json_encode($chartTanggal) !!},
            datasets: [
                {
                    label: 'Hadir',
                    data: {!! json_encode($chartHadir) !!},
                    borderColor: '#34d399',
                    backgroundColor: '#34d399',
                    fill: false,
                    tension: 0.3,
                },
                {
                    label: 'Sakit',
                    data: {!! json_encode($chartSakit) !!},
                    borderColor: '#fbbf24',
                    backgroundColor: '#fbbf24',
                    fill: false,
                    tension: 0.3,
                },
                {
                    label: 'Izin',
                    data: {!! json_encode($chartIzin) !!},
                    borderColor: '#60a5fa',
                    backgroundColor: '#60a5fa',
                    fill: false,
                    tension: 0.3,
                },
                {
                    label: 'Alfa',
                    data: {!! json_encode($chartAlfa) !!},
                    borderColor: '#ef4444',
                    backgroundColor: '#ef4444',
                    fill: false,
                    tension: 0.3,
                }
            ]
        };
        new Chart(document.getElementById('linePresensi'), {
            type: 'line',
            data: lineChartData,
            options: {
                plugins: {
                    legend: { labels: { color: document.documentElement.classList.contains('dark') ? '#fff' : '#000' } }
                },
                scales: {
                    x: { ticks: { color: document.documentElement.classList.contains('dark') ? '#fff' : '#000' } },
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: document.documentElement.classList.contains('dark') ? '#fff' : '#000' }
                    }
                }
            }
        });
    </script>
</x-app-layout>
