<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
        {{-- <x-dashboard.card title="Total Siswa" :value="$totalSiswa" icon="users" color="blue" /> --}}
        {{-- <x-dashboard.card title="Siswa Aktif ({{ $tahunAjaranAktif->nama ?? '-' }})" :value="$totalSiswaAktif" icon="user-check" color="orange" /> --}}
                {{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\dashboard\admin.blade.php --}}

        <x-dashboard.card
            title="Siswa Aktif ({{ $tahunAjaranAktif && $tahunAjaranAktif->tahunAjaran ? $tahunAjaranAktif->tahunAjaran->tahun : '-' }})"
            :value="$totalSiswaAktif" icon="user-check" color="orange" />
        <x-dashboard.card title="Total Guru" :value="$totalGuru" icon="chalkboard-teacher" color="green" />
        <x-dashboard.card title="Jumlah Mapel" :value="$totalMapel" icon="book" color="purple" />
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
        {{-- <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Statistik Nilai & Kegiatan</h3>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3 mt-4">
            <x-dashboard.card title="Nilai P5 Terinput" :value="$nilaiP5" icon="star" color="yellow" />
        <x-dashboard.card title="Nilai Ekstra" :value="$nilaiEkstra" icon="running" color="pink" />
        <x-dashboard.card title="Rapor Final" :value="$raporSelesai" icon="file-alt" color="teal" />
        </div> --}}
        <x-dashboard.card title="Jumlah Lulusan" :value="$totalLulusan" icon="user-graduate" color="red" />
    </div>

    <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-4">Grafik Total Siswa per Tahun Ajaran</h3>
        <canvas id="chartSiswa"></canvas>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('chartSiswa').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartSiswa->pluck('label')) !!},
                    datasets: [{
                        label: 'Total Siswa',
                        data: {!! json_encode($chartSiswa->pluck('total')) !!},
                        backgroundColor: 'rgba(59, 130, 246, 0.7)'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        </script>
    @endpush

</x-app-layout>
