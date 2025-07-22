@foreach ($kelas as $item)
    <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit Kelas" maxWidth="2xl">
        <form action="{{ route('kelas.update', $item['id']) }}" method="POST"
            class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')

            {{-- Kelas --}}
            <x-form.input label="Nama Kelas" name="nama" :value="$item['nama']" required />

            {{-- Wali Kelas --}}
            <x-form.select
                name="wali_kelas_id"
                label="Wali Kelas"
                :options="$guru"
                placeholder="Pilih Wali Kelas"
                :selected="$item['wali_kelas_id']"
                :searchable="true"
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
@endforeach
