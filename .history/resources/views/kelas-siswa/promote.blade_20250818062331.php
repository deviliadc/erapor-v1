<x-modal name="form-promote-siswa" title="Promosikan Siswa ke Kelas Baru" maxWidth="2xl">
    <form action="{{ role_route('kelas.siswa.promote') }}" method="POST" enctype="multipart/form-data"
        class="space-y-6 sm:p-6">
        @csrf
        <input type="hidden" name="tahun_lama_id"
            value="{{ request('tahun_semester_filter') ?? ($tahunAktif->id ?? null) }}">
        <input type="hidden" name="kelas_lama_id" value="{{ $kelas->id }}">

        {{-- Readonly Kelas Saat Ini --}}
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Dari Kelas
            </label>
            <div
                class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                {{ $kelas->nama }}
            </div>
        </div>

        <x-form.select name="kelas_baru_id" label="Ke Kelas" :options="$kelasList" required />

        <x-form.select name="tahun_baru_id" label="Tahun Ajaran Baru" :options="$tahunAjaranOptions" value-key="id" label-key="name"
            required />

        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                Simpan
            </button>
        </div>
    </form>
</x-modal>
