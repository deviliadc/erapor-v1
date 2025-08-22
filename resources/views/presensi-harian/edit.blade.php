@foreach ($data as $item)
    <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit Presensi Harian" maxWidth="2xl">
        <form action="{{ role_route('presensi-harian.update', ['presensi_harian' => $item['id']]) }}" method="POST"
            class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')
            {{-- <input type="hidden" name="kelas_id" value="{{ $item['kelas_id'] }}"> --}}
            <input type="hidden" name="tahun_semester_id" value="{{ $item['tahun_semester_id'] }}">

            {{-- Tanggal (readonly) --}}
            <x-form.input
                label="Tanggal"
                name="tanggal"
                :value="\Carbon\Carbon::parse($item['tanggal'])->translatedFormat('l, d F Y')"
                class="cursor-not-allowed bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400"
                readonly
            />

            {{-- Kelas (readonly) --}}
            <x-form.input
                label="Kelas"
                name="kelas"
                :value="$item['kelas']"
                readonly
                class="cursor-not-allowed bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400"
            />

            {{-- Catatan --}}
            <x-form.textarea
                label="Catatan"
                name="catatan"
                placeholder="Masukkan catatan"
                rows="4"
                :value="old('catatan', $item['catatan'])"
            />

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>
@endforeach
