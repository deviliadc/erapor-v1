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
    {{ $ts->tahunAjaran->tahun }} - {{ ucfirst($ts->semester) }}
</option>

                @endforeach
            </select>
        </form>

    {{-- Chart Bar --}}
<div class="flex justify-center mb-8">
    <div class="w-full max-w-md bg-white dark:bg-gray-900 border dark:border-gray-700 rounded-lg shadow p-4 flex items-center justify-center">
        <canvas id="barAbsensi" width="320" height="200" style="max-width:320px;max-height:200px;"></canvas>
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
    // Horizontal bar chart: satu baris per status
    const labels = ['Hadir', 'Sakit', 'Izin', 'Alfa'];
    const data = {
        labels: labels,
        datasets: [{
            label: 'Jumlah',
            data: [
                @json($chartHadir[0]),
                @json($chartSakit[0]),
                @json($chartIzin[0]),
                @json($chartAlfa[0])
            ],
            backgroundColor: [
                '#34d399', // Hadir
                '#fbbf24', // Sakit
                '#60a5fa', // Izin
                '#ef4444'  // Alfa
            ]
        }]
    };

    new Chart(document.getElementById('barAbsensi'), {
        type: 'bar',
        data: data,
        options: {
            indexAxis: 'y', // horizontal bar
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Rekap Absensi Siswa',
                    color: document.documentElement.classList.contains('dark') ? '#fff' : '#000'
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, color: document.documentElement.classList.contains('dark') ? '#fff' : '#000' }
                },
                y: {
                    ticks: { color: document.documentElement.classList.contains('dark') ? '#fff' : '#000' }
                }
            }
        }
    });
</script>

</x-app-layout>
