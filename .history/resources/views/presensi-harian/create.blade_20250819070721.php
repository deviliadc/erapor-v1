<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />
    <div class="space-y-6">

        <!-- Wrapper -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-5 py-4 sm:px-6 sm:py-5">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                    Tambah Presensi Harian
                </h3>
            </div>

            {{-- Tahun Aktif saat ini --}}
            <div class="px-5 py-4 sm:px-6 sm:py-5">
                <h4 class="text-sm font-medium text-gray-800 dark:text-white/90">
                    Tahun Ajaran Aktif: {{ $tahun }}
                </h4>
            </div>

            <form method="GET" action="{{ role_route('presensi-harian.create') }}" class="p-5 space-y-6">
                <div class="flex flex-col sm:flex-row gap-4 items-end">
                    <div class="flex-1">
                        <x-form.select
                            name="kelas_id"
                            label="Pilih Kelas"
                            :options="$kelas->pluck('nama', 'id')"
                            placeholder="-- Pilih Kelas --"
                            :selected="request()->filled('kelas_id') ? request('kelas_id') : ''"
                            required
                            onchange="this.form.submit()"
                        />
                    </div>
                </div>
            </form>

            <!-- Form Presensi -->
            @if (request('kelas_id'))
                <form method="POST" action="{{ role_route('presensi-harian.store') }}" class="px-5 pb-5 space-y-6 mt-4">
                    @csrf
                    <input type="hidden" name="kelas_id" value="{{ request('kelas_id') }}">
                    {{-- <input type="hidden" name="periode" value="{{ request('periode', 'tengah') }}"> --}}

                    <!-- Tanggal Presensi -->
                    <x-form.date-picker
                        label="Tanggal"
                        name="tanggal"
                        :value="old('tanggal')"
                        placeholder="Pilih tanggal"
                        required
                        x-data
                        x-init="
                            flatpickr($el, {
                                dateFormat: 'Y-m-d',
                                maxDate: new Date(),
                                disable: [
                                    function(date) {
                                        return date.getDay() === 6 || date.getDay() === 0;
                                    }
                                ],
                                locale: 'id'
                            });
                        "
                        class="bg-white dark:bg-gray-900 dark:text-white"
                    />

                    <!-- Daftar Siswa -->
                    <div class="mb-6">
                        <label class="block mb-2 font-semibold text-gray-700 dark:text-white">Daftar Siswa</label>
                        <div class="overflow-x-auto rounded border border-gray-200 dark:border-gray-700">
                            <table class="min-w-full table-auto text-sm text-gray-700 dark:text-gray-400">
                                <thead class="bg-gray-100 dark:bg-gray-800 tborder-y border-gray-100 py-3 dark:border-gray-800">
                                    <tr>
                                        <th class="border border-gray-200 dark:border-gray-700 px-2 py-1 text-center w-16">Absen</th>
                                        <th class="border border-gray-200 dark:border-gray-700 px-2 py-1">Nama Siswa</th>
                                        <th class="border border-gray-200 dark:border-gray-700 px-2 py-1 text-center">Presensi</th>
                                        <th class="border border-gray-200 dark:border-gray-700 px-2 py-1 text-center">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($siswa as $ks)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                            <td class="border border-gray-200 dark:border-gray-700 px-2 py-1 text-center">
                                                {{ $ks->no_absen ?? '-' }}
                                            </td>
                                            <td class="border border-gray-200 dark:border-gray-700 px-2 py-1">
                                                {{ $ks->siswa->nama }}
                                            </td>
                                            <td class="border border-gray-200 dark:border-gray-700 px-2 py-1 text-center">
                                                <div class="flex flex-wrap gap-2 justify-center">
                                                    @foreach (['Hadir', 'Izin', 'Sakit', 'Alpha'] as $value)
                                                        <label class="inline-flex items-center space-x-1">
                                                            <input
                                                                type="radio"
                                                                name="status[{{ $ks->id }}]"
                                                                value="{{ $value }}"
                                                                required
                                                                class="text-brand-500 dark:bg-gray-900 dark:border-gray-600"
                                                            >
                                                            <span>{{ $value }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="border border-gray-200 dark:border-gray-700 px-2 py-1 text-center">
                                                <input
                                                    type="text"
                                                    name="keterangan[{{ $ks->id }}]"
                                                    class="w-full border rounded px-2 py-1 dark:bg-gray-900 dark:border-gray-600 dark:text-white"
                                                >
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="border border-gray-200 dark:border-gray-700 px-2 py-2 text-center text-red-500 dark:text-red-400">
                                                Tidak ada siswa di kelas ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <x-form.textarea
                        name="catatan"
                        label="Catatan"
                        placeholder="Masukkan catatan jika ada"
                        class="dark:bg-gray-900 dark:text-white dark:border-gray-600"
                    />

                    <!-- Tombol Submit -->
                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600"
                        >
                            Simpan
                        </button>
                    </div>
                </form>
            @endif

        </div>
    </div>

    <!-- Styling Flatpickr Custom untuk Dark Mode -->
    <style>
        .flatpickr-day.disabled,
        .flatpickr-day.flatpickr-disabled,
        .flatpickr-day:not(.prevMonthDay):not(.nextMonthDay):not(.today).flatpickr-disabled {
            background: #f3f4f6 !important;
            color: #b0b0b0 !important;
            cursor: not-allowed !important;
            opacity: 1 !important;
        }

        .dark .flatpickr-calendar {
            background: #1f2937 !important; /* gray-800 */
            color: #f9fafb !important; /* gray-50 */
        }

        .dark .flatpickr-day:hover {
            background: #374151 !important; /* gray-700 */
        }

        .dark .flatpickr-day.selected {
            background: #3b82f6 !important; /* blue-500 */
        }
    </style>
</x-app-layout>
