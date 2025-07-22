@foreach ($tema as $item)
    <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit P5 Tema" maxWidth="2xl">
        <form action="{{ route('p5-tema.update', ['p5_tema' => $item['id'], 'tab' => request('tab', 'tema')]) }}" method="POST"
            class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')
            {{-- <input type="hidden" name="tab" value="{{ request('tab', 'tema') }}"> --}}
            <input type="hidden" name="tab" value="tema">

            {{-- Nama Tema --}}
            <x-form.input label="Nama Tema" name="nama_tema"
                :value="old('nama_tema', $item['nama_tema'])" required />

            {{-- Deskripsi --}}
            <x-form.textarea label="Deskripsi" name="deskripsi_tema"
            placeholder="Masukkan deskripsi" rows="4" :value="old('deskripsi_tema', $item['deskripsi_tema'])" />

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>
@endforeach
