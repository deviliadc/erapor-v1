@foreach ($fase as $item)
    <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit Fase" maxWidth="2xl">
        {{-- <form action="{{ role_route('fase.update', $item['id']) }}" method="POST" --}}
            <form action="{{ role_route('fase.update', ['fase' => $item['id']]) }}" method="POST"
            class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')

            {{-- Nmma --}}
            <x-form.input
                label="Nama"
                name="nama"
                :value="$item['nama']"
                required />

            {{-- Keterangan --}}
            <x-form.textarea
                label="Keterangan"
                name="keterangan"
                :value="$item['keterangan']"
                placeholder="Masukkan keterangan"
                rows="3" />

            {{-- Tombol Submit --}}
            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>
@endforeach
