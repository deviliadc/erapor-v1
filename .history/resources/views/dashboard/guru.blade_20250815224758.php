<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    @php
        $guru = auth()->user()->guru;
        $isGuruAktif = $guru && \App\Models\GuruKelas::where('guru_id', $guru->id)->exists();
    @endphp

    @if (!$isGuruAktif)
        <div class="mt-10 text-center text-lg text-gray-700 dark:text-white/80">
            Halo, {{ $guru->nama ?? '-' }}.<br>
            Anda belum terdaftar sebagai pengajar maupun wali kelas di kelas manapun.<br>
            <span class="text-sm text-gray-500">Silakan hubungi admin jika terjadi kesalahan.</span>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
            <x-dashboard.card title="Total Siswa" :value="$totalSiswa" icon="users" color="blue" />
            {{-- <x-dashboard.card title="Total Guru" :value="$totalGuru" icon="chalkboard-teacher" color="green" /> --}}
            <x-dashboard.card title="Jumlah Mapel" :value="$totalMapel" icon="book" color="purple" />
        </div>

        <div class="mt-8">
            {{-- <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Statistik Nilai & Kegiatan</h3> --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3 mt-4">
                {{-- <x-dashboard.card title="Nilai P5 Terinput" :value="$nilaiP5" icon="star" color="yellow" />
        <x-dashboard.card title="Nilai Ekstra" :value="$nilaiEkstra" icon="running" color="pink" />
        <x-dashboard.card title="Rapor Final" :value="$raporSelesai" icon="file-alt" color="teal" /> --}}
            </div>
        </div>
    @endif
</x-app-layout>
