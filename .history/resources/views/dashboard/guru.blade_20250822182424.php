<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    @php
        $guru = auth()->user()->guru;
        $guruKelas = $guru ? \App\Models\GuruKelas::with(['kelas', 'mapel'])->where('guru_id', $guru->id)->get() : collect();
        $isGuruAktif = $guru && $guruKelas->isNotEmpty();
    @endphp

    @if (!$isGuruAktif)
        <div class="mt-10 text-center text-lg text-gray-700 dark:text-white/80">
            Halo, {{ $guru->nama ?? '-' }}.<br>
            Anda belum terdaftar sebagai pengajar maupun wali kelas di kelas manapun.<br>
            <span class="text-sm text-gray-500">Silakan hubungi admin jika terjadi kesalahan.</span>
        </div>
    @else
        {{-- Statistik singkat --}}
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
            <x-dashboard.card title="Total Siswa" :value="$totalSiswa" icon="users" color="blue" />
            <x-dashboard.card title="Jumlah Mapel" :value="$totalMapel" icon="book" color="purple" />
        </div>

        {{-- Info kelas yang diajar --}}
        <div class="mt-10">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-3">
                Informasi Kelas yang Anda Ajar
            </h3>
            <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-2xl">
                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Kelas</th>
                            <th class="px-4 py-3">Mata Pelajaran</th>
                            <th class="px-4 py-3">Peran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($guruKelas as $index => $item)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                <td class="px-4 py-2">{{ $item->kelas->nama }}</td>
                                <td class="px-4 py-2">{{ $item->mapel->nama ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    @if ($item->wali)
                                        <span class="px-2 py-1 text-xs font-medium text-white bg-green-600 rounded-lg">
                                            Wali Kelas
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium text-white bg-blue-600 rounded-lg">
                                            Pengajar
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-app-layout>
