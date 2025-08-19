<x-modal name="form-generate-absen" title="Generate Nomor Absen" maxWidth="md">
    <form action="{{ role_route('kelas-siswa.generate-absen', [
        'kelas' => $kelas->id, ]) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="tahun_ajaran_filter" value="{{ $tahunAjaranId }}">
        <div class="p-6">
            <p>Urutkan ulang nomor absen berdasarkan nama siswa?</p>
            <div class="flex justify-center mt-4 p-4">
                <button type="submit" class="px-4 py-2 bg-brand-500 text-white rounded-lg hover:bg-brand-600">
                    Ya, Generate
                </button>
            </div>
        </div>
    </form>
</x-modal>
