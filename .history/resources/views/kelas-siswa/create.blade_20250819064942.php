{{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\kelas\siswa\create.blade.php --}}
<x-modal name="form-create-kelas-siswa" title="Tambah Siswa" maxWidth="2xl">
    <form action="{{ role_route('kelas-siswa.store', ['kelas' => $kelas->id]) }}" method="POST"
        enctype="multipart/form-data" class="space-y-6 sm:p-6">
        @csrf
        <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaranId }}">
        <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
        {{-- Siswa --}}
        @if (empty($siswaOptions))
            <div class="text-center text-gray-500 py-4">
                Tidak ada siswa yang tersedia untuk ditambahkan.
            </div>
            <div class="flex justify-end">
                <a href="{{ url('/siswa') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Tambah Siswa Baru
                </a>
            </div>
        @else
            <x-form.select
                name="siswa_id[]"
                label="Siswa"
                :options="$siswaOptions" placeholder="Pilih Siswa"
                :selected="old('siswa_id', [])" :searchable="true" multiple required />

            {{-- Tombol Submit --}}
            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Tambah
                </button>
            </div>
        @endif
    </form>
</x-modal>
