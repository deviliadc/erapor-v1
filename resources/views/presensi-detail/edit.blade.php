<x-app-layout>
    <div class="max-w-3xl mx-auto p-6">
        <h2 class="text-xl font-bold mb-4">Tambah Presensi Harian</h2>

        {{-- Langkah 1: Pilih Kelas --}}
        <form method="GET" action="{{ route('presensi-harian.create') }}" class="mb-6">
            <x-form.select name="kelas_id" label="Pilih Kelas" :options="$kelas->pluck('nama', 'id')" placeholder="-- Pilih Kelas --"
                :selected="request()->filled('kelas_id') ? request('kelas_id') : ''" required onchange="this.form.submit()" />
        </form>


        {{-- Langkah 2: Jika kelas dipilih, tampilkan form presensi --}}
        @if (request('kelas_id'))
            <form method="POST" action="{{ route('presensi-harian.store') }}">
                @csrf
                <input type="hidden" name="kelas_id" value="{{ request('kelas_id') }}">
                {{-- <input type="hidden" name="input_by" value="{{ auth()->user()->id }}"> --}}

                {{-- Tanggal Presensi --}}
                <x-form.date-picker label="Tanggal" name="tanggal" :value="old('tanggal')" placeholder="Pilih tanggal"
                    required />

                {{-- Daftar Siswa --}}
                @if ($siswa->count())
                    <div class="mb-6">
                        <label class="block mb-2 font-semibold">Daftar Siswa</label>
                        <div class="overflow-x-auto rounded border">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-100 dark:bg-gray-800">
                                    <tr>
                                        <th class="border px-2 py-1 text-center w-12">No</th>
                                        <th class="border px-2 py-1 text-center w-16">Absen</th>
                                        <th class="border px-2 py-1">Nama Siswa</th>
                                        <th class="border px-2 py-1 text-center">Presensi</th>
                                        <th class="border px-2 py-1 text-center">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Looping siswa --}}
                                    @foreach ($siswa as $i => $ks)
                                        <tr class="hover:bg-gray-50">
                                            <td class="border px-2 py-1 text-center">{{ $i + 1 }}</td>
                                            <td class="border px-2 py-1 text-center">{{ $ks->no_absen ?? '-' }}</td>
                                            <td class="border px-2 py-1">{{ $ks->siswa->nama }}</td>
                                            <td class="border px-2 py-1 text-center">
                                                <div class="flex flex-wrap gap-2 justify-center">
                                                    @foreach (['Hadir' => 'Hadir', 'Izin' => 'Izin', 'Sakit' => 'Sakit', 'Alpha' => 'Alpha'] as $value => $label)
                                                        <label class="inline-flex items-center space-x-1">
                                                            <input type="radio" name="status[{{ $ks->id }}]"
                                                                value="{{ $value }}" required>
                                                            <span>{{ $label }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="border px-2 py-1 text-center">
                                                <input type="text" name="keterangan[{{ $ks->id }}]"
                                                    class="w-full border rounded px-2 py-1">
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="mb-6 text-sm text-red-500">Tidak ada siswa di kelas ini.</div>
                @endif

                {{-- Catatan --}}
                <x-form.textarea name="catatan" label="Catatan" placeholder="Masukkan catatan jika ada"
                    class="mb-6" />

                {{-- Tombol Submit --}}
                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                        Simpan
                    </button>
                </div>
            </form>
        @endif
    </div>
</x-app-layout>
