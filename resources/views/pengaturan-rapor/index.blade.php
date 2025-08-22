<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <div class="max-w-xl mx-auto mt-8 rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <form action="{{ role_route('pengaturan-rapor.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun Semester</label>
                <div class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                    {{ $tahunAktif->tahunAjaran->tahun ?? '-' }} - {{ ucfirst($tahunAktif->semester ?? '-') }}
                </div>
            </div>

            <x-form.input
                label="Nama Kepala Sekolah"
                name="nama_kepala_sekolah"
                :value="old('nama_kepala_sekolah', $data?->nama_kepala_sekolah ?? '')"
                required
            />

            <x-form.input
                label="NIP Kepala Sekolah"
                name="nip_kepala_sekolah"
                :value="old('nip_kepala_sekolah', $data?->nip_kepala_sekolah ?? '')"
            />

            <x-form.input
                label="Jabatan"
                name="jabatan"
                :value="old('jabatan', $data?->jabatan ?? '')"
                required
            />

            <x-form.input
                label="Tempat"
                name="tempat"
                :value="old('tempat', $data?->tempat ?? '')"
                required
            />

            <x-form.date-picker
                label="Tanggal Cetak"
                name="tanggal_cetak"
                type="date"
                :value="old('tanggal_cetak', ($data?->tanggal_cetak
                    ? \Carbon\Carbon::parse($data->tanggal_cetak)->format('Y-m-d')
                    : \Carbon\Carbon::now()->format('Y-m-d')
                ))"
                required
            />

            {{-- <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanda Tangan Kepala Sekolah</label>
                @if ($data?->ttd_url)
                    <img src="{{ $data->ttd_url }}" alt="TTD Kepala Sekolah" class="h-16 mb-2">
                @endif
                <input type="file" name="ttd" accept="image/*" class="block w-full text-sm text-gray-700 dark:text-gray-300">
                <small class="text-xs text-gray-500">Format gambar JPG/PNG, maksimal 2MB.</small>
            </div> --}}

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Upload Tanda Tangan Kepala Sekolah
                </label>
                @if ($data?->ttd_url)
                    <img src="{{ $data->ttd_url }}" alt="TTD Kepala Sekolah" class="h-16 mb-2">
                @endif
                <input type="file"
                    name="ttd"
                    accept="image/*"
                    class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:text-white/90 dark:file:border-gray-800 dark:file:bg-white/[0.03] dark:file:text-gray-400 dark:placeholder:text-gray-400">
                <small class="text-xs text-gray-500">Format gambar JPG/PNG, maksimal 2MB.</small>
            </div>

            <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                        Simpan Pengaturan
                    </button>
            </div>
        </form>
    </div>
</x-app-layout>
