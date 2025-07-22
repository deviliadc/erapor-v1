@foreach ($tujuanPembelajaran as $item)
    <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit Tujuan Pembelajaran" maxWidth="2xl">
        <form action="{{ route('tujuan-pembelajaran.update', ['tujuan_pembelajaran' => $item['id']]) }}" method="POST"
            class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="tab" value="tujuan-pembelajaran">

            {{-- Pilih Lingkup Materi --}}
            <x-form.select
                label="Lingkup Materi"
                name="lingkup_materi_id"
                :options="$lingkupMateriOptions"
                :selected="$item['lingkup_materi_id']"
            />

            {{-- Subbab --}}
            <x-form.input label="Subbab" name="subbab" :value="$item['subbab']"  />

            {{-- Tujuan Pembelajaran --}}
            <x-form.textarea label="Tujuan Pembelajaran" name="tujuan" :value="$item['tujuan']" />

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>
@endforeach
