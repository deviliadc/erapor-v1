@foreach ($ekstra as $item)
    <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit Ekstrakurikuler" maxWidth="2xl">
        <form action="{{ role_route('ekstra.update', ['ekstra' => $item['id']]) }}" method="POST"
            class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <x-form.input
                label="Nama"
                name="nama"
                :value="old('nama', $item['nama'])"
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
