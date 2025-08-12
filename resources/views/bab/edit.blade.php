@foreach ($bab as $item)
    <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit Bab" maxWidth="2xl">
        <form action="{{ role_route('bab.update', ['bab' => $item['id']], ['tab' => request('tab', 'bab')]) }}" method="POST"
            class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')
            {{-- <input type="hidden" name="tab" value="{{ request('tab', 'dimensi') }}"> --}}
            <input type="hidden" name="tab" value="bab">

            {{-- Nama Bab --}}
            <x-form.input
                label="Nama Bab"
                name="nama"
                :value="$item['nama']"
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
