{{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\dashboard\kepala-sekolah.blade.php --}}
<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4 mb-8">
        <x-dashboard.card title="Total Siswa" :value="$totalSiswa" icon="users" color="blue" />
        <x-dashboard.card title="Total Guru" :value="$totalGuru" icon="chalkboard-teacher" color="green" />
        <x-dashboard.card title="Jumlah Mapel" :value="$totalMapel" icon="book" color="purple" />
        <x-dashboard.card title="Total Wali Kelas" :value="$totalWali ?? '-'" icon="user-tie" color="pink" />
    </div>

    {{-- <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
        <x-dashboard.card title="Nilai P5 Terinput" :value="$nilaiP5 ?? '-'" icon="star" color="yellow" />
        <x-dashboard.card title="Nilai Ekstra" :value="$nilaiEkstra ?? '-'" icon="running" color="pink" />
        <x-dashboard.card title="Rapor Final" :value="$raporSelesai ?? '-'" icon="file-alt" color="teal" />
    </div> --}}
</x-app-layout>
