@foreach ($dimensi as $item)
    <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit P5 Dimensi" maxWidth="2xl">
        <form action="{{ role_route('p5-dimensi.update', ['p5_dimensi' => $item['id']], ['tab' => request('tab', 'dimensi')]) }}" method="POST"
            class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="tab" value="dimensi">
            {{-- Nama Dimensi --}}
            <x-form.input label="Nama Dimensi" name="nama_dimensi" type="text"
                placeholder="Masukkan nama dimensi" required
                :value="old('nama_dimensi', $item['nama_dimensi'])" />
            {{-- Deskripsi --}}
            <x-form.textarea label="Deskripsi" name="deskripsi_dimensi" rows="3"
                placeholder="Masukkan deskripsi dimensi" required
                :value="old('deskripsi_dimensi', $item['deskripsi_dimensi'])" />
            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>
@endforeach
