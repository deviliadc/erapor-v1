<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />
    <div class="container mx-auto py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold mb-4 text-brand-500">Data Diri Siswa</h2>
                <div class="space-y-2">
                    <div><span class="font-medium">Nama:</span> {{ $siswa->nama }}</div>
                    <div><span class="font-medium">NIS:</span> {{ $siswa->nis }}</div>
                    <div><span class="font-medium">NISN:</span> {{ $siswa->nisn }}</div>
                    <div><span class="font-medium">Jenis Kelamin:</span> {{ $siswa->jenis_kelamin }}</div>
                    <div><span class="font-medium">Tempat, Tanggal Lahir:</span> {{ $siswa->tempat_lahir }},
                        {{ $siswa->tanggal_lahir }}</div>
                    <div><span class="font-medium">Alamat:</span> {{ $siswa->alamat }}</div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold mb-4 text-brand-500">Data Orang Tua & Wali</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h3 class="font-semibold text-brand-400 mb-2">Ayah</h3>
                        <div class="space-y-1 text-sm">
                            <div><span class="font-medium">Nama:</span> {{ $siswa->nama_ayah }}</div>
                            <div><span class="font-medium">Pekerjaan:</span> {{ $siswa->pekerjaan_ayah }}</div>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-brand-400 mb-2">Ibu</h3>
                        <div class="space-y-1 text-sm">
                            <div><span class="font-medium">Nama:</span> {{ $siswa->nama_ibu }}</div>
                            <div><span class="font-medium">Pekerjaan:</span> {{ $siswa->pekerjaan_ibu }}</div>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-brand-400 mb-2">Wali</h3>
                        <div class="space-y-1 text-sm">
                            <div><span class="font-medium">Nama:</span> {{ $siswa->nama_wali }}</div>
                            <div><span class="font-medium">Pekerjaan:</span> {{ $siswa->pekerjaan_wali }}</div>
                        </div>
                    </div>
                    <div><span class="font-medium">Alamat:</span> {{ $siswa->alamat_wali ?? $siswa->alamat }}</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold mb-4 text-brand-500">Nilai Siswa</h2>
            <canvas id="chartNilai" height="100"></canvas>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const labels = {!! json_encode($chartLabels ?? []) !!};
            const dataNilai = {!! json_encode($chartData ?? []) !!};
            const ctx = document.getElementById('chartNilai').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Nilai',
                        data: dataNilai,
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                stepSize: 10
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
