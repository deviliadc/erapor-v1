<x-modal name="form-promote-siswa" title="Promosikan Siswa ke Kelas Baru" maxWidth="2xl">
    <form action="{{ role_route('kelas.siswa.promote') }}" method="POST" enctype="multipart/form-data" class="space-y-6 sm:p-6">
        @csrf
        <input type="hidden" name="tahun_lama_id" value="{{ request('tahun_semester_filter') ?? ($tahunAktif->id ?? null) }}">

        <x-form.select
            name="kelas_lama_id"
            label="Dari Kelas"
            :options="$kelasList"
            :selected="$kelas->id"
            required
        />

        <x-form.select
        name="kelas_baru_id"
        label="Ke Kelas"
        :options="$kelasList"
        required
        />

        <x-form.select
            name="tahun_baru_id"
            label="Tahun Ajaran Baru"
            :options="$tahunAjaranList"
            value-key="id"
            label-key="name"
            required
        />

        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                Simpan
            </button>
        </div>
    </form>
</x-modal>
