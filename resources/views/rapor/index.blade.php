<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <div class="mt-4 rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">

        {{-- Filter Form --}}
        <form method="GET" action="{{ role_route('rapor.index') }}" class="mb-6 flex flex-wrap items-end gap-4">
            {{-- Tahun Semester --}}
            <div>
                <label for="tahun_semester_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Tahun Semester
                </label>
                <select name="tahun_semester_id" id="tahun_semester_id" onchange="this.form.submit()"
                    class="h-9 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    @foreach ($daftarTahunSemester as $ts)
                        <option value="{{ $ts->id }}"
                            {{ $ts->id == request('tahun_semester_id', $tahunAktif->id) ? 'selected' : '' }}>
                            {{ $ts->tahun }} - {{ ucfirst($ts->semester) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Kelas --}}
            <div>
                <x-form.select
                    name="kelas_id"
                    label="Kelas"
                    :options="$kelasList->pluck('nama', 'id')->map(fn($nama) => 'Kelas ' . $nama)"
                    :selected="request('kelas_id', $kelas_id)"
                    placeholder="Semua Kelas"
                    onchange="this.form.submit()"
                />
            </div>

            {{-- Periode --}}
            <div>
                <x-form.select
                    name="periode"
                    label="Periode"
                    :options="['tengah' => 'Tengah Semester', 'akhir' => 'Akhir Semester']"
                    :selected="request('periode', $periode)"
                    required
                    onchange="this.form.submit()"
                />
            </div>
        </form>

        {{-- Tombol Ekspor --}}
        <div class="mb-4 mt-4 flex justify-end">
            <a href="{{ role_route('rapor.export', [
                'tahun_semester_id' => $tahunSemesterId,
                'kelas_id' => $kelas_id,
                'periode' => $periode,
            ]) }}"
                class="inline-flex w-36 items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                Ekspor Rapor
            </a>
        </div>

        {{-- Tabel Siswa --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-white">No. Absen</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-white">Nama</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-white">Kelas</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-white">NIS</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-white">NISN</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($siswaList as $i => $siswa)
                        <tr class="border-b dark:border-gray-800">
                            <td class="px-4 py-2">{{ $i + 1 }}</td>
                            <td class="px-4 py-2">{{ $siswa->nama }}</td>
                            <td class="px-4 py-2">
                                {{ $siswa->kelasSiswa->where('tahun_semester_id', $tahunSemesterId)->first()?->kelas?->nama ?? '-' }}
                            </td>
                            <td class="px-4 py-2">{{ $siswa->nis }}</td>
                            <td class="px-4 py-2">{{ $siswa->nisn }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ role_route('rapor.show', [
                                    'rapor' => $siswa->id,
                                    'tahun_semester_id' => $tahunSemesterId,
                                    'periode' => $periode,
                                ]) }}" class="text-brand-500 hover:underline">
                                    Lihat Rapor
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-2 text-center text-gray-500">Tidak ada data siswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
