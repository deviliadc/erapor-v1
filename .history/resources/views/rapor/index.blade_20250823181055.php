<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    {{-- Filter Tahun Semester & Kelas --}}
    <form method="GET" class="mb-4 flex flex-wrap gap-4">
        <div class="flex-1 min-w-0">
            <x-form.select label="Tahun Semester" name="tahun_semester_id" :options="$daftarTahunSemester->mapWithKeys(
                fn($ts) => [$ts->id => ($ts->tahunAjaran->tahun ?? '-') . ' - ' . ucfirst($ts->semester)],
            )" :selected="$tahunSemesterId"
                placeholder="Pilih Tahun Semester" searchable required onchange="this.form.submit()" />
        </div>
        <div class="flex-1 min-w-0">
            <x-form.select label="Kelas" name="kelas_id" :options="['' => '-- Pilih Kelas --'] +
                $kelasList->mapWithKeys(fn($kls) => [$kls->id => $kls->nama])->toArray()" :selected="$kelas_id" placeholder="Pilih Kelas"
                searchable onchange="this.form.submit()" />
        </div>
    </form>

    {{-- Table siswa --}}
    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white p-4">
        <table class="w-full text-sm text-center table-auto">
            <thead class="bg-gray-100 sticky top-0">
                <tr>
                    <th class="px-3 py-2">No</th>
                    <th class="px-3 py-2">Nama</th>
                    <th class="px-3 py-2">NIPD</th>
                    <th class="px-3 py-2">NISN</th>
                    <th class="px-3 py-2">Jenis Kelamin</th>
                    <th class="px-3 py-2">Kelas</th>
                    <th class="px-3 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswaList as $i => $siswa)
                    <tr>
                        <td class="px-3 py-2">{{ $i + 1 }}</td>
                        <td class="px-3 py-2 text-left">{{ $siswa->nama }}</td>
                        <td class="px-3 py-2">{{ $siswa->nipd ?? '-' }}</td>
                        <td class="px-3 py-2">{{ $siswa->nisn ?? '-' }}</td>
                        <td class="px-3 py-2">{{ $siswa->jenis_kelamin ?? '-' }}</td>
                        {{-- <td class="px-3 py-2">{{ $siswa->kelasSiswa->kelas->nama ?? '-' }}</td> --}}
                        <td class="px-3 py-2">
                            {{ optional($siswa->kelasSiswa->first()?->kelas)->nama ?? '-' }}
                        </td>
                        <td class="px-3 py-2 flex flex-wrap gap-2 justify-center">
                            {{-- <a href="{{ role_route('rapor.download', ['siswa' => $siswa->id, 'type' => 'kelengkapan']) }}"
                                target="_blank"
                                class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-warning-500 shadow-theme-xs hover:bg-warning-600">
                                Kelengkapan
                            </a> --}}
                            <a href="{{ role_route('rapor.cetakKelengkapan', ['siswa' => $siswa->id, 'tahun_semester_id' => $tahunSemesterId]) }}"
                                target="_blank"
                                class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-warning-500 shadow-theme-xs hover:bg-warning-600">
                                Kelengkapan
                            </a>

                            {{-- <a href="{{ role_route('rapor.cetakTengah', ['siswa' => $siswa->id, 'tahun_semester_id' => $tahunSemesterId]) }}"
                                target="_blank"
                                class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-blue-light-500 shadow-theme-xs hover:bg-blue-light-600">
                                Rapor Tengah Semester
                            </a> --}}
                            <a href="{{ route('rapor.cetakUTS', [...]) }}"
    target="_blank"
    class="rounded-lg px-4 py-3 text-sm font-medium shadow-theme-xs
        {{ $isValidUTS ? 'bg-brand-500 text-white hover:bg-brand-600' : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}"
    {{ $isValidUTS ? '' : 'onclick="return false;" tabindex="-1"' }}>
    Cetak Rapor UTS
</a>

                            <a href="{{ role_route('rapor.cetakAkhir', ['siswa' => $siswa->id, 'tahun_semester_id' => $tahunSemesterId]) }}"
                                target="_blank"
                                class="rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                                Rapor Akhir Semester
                            </a>

                            <a href="{{ role_route('rapor.cetakP5', ['siswa' => $siswa->id, 'tahun_semester_id' => $tahunSemesterId]) }}"
                                target="_blank"
                                class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-success-500 shadow-theme-xs hover:bg-success-600">
                                Rapor P5
                            </a>
                        </td>


                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-4 text-gray-500">Tidak ada data siswa.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
