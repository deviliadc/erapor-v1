@foreach ($parameterEkstra as $item)
    <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit Parameter Ekstrakurikuler" maxWidth="2xl">
        <form action="{{ role_route('param-ekstra.update', ['param_ekstra' => $item['id']], ['tab' => request('tab', 'parameter')]) }}" method="POST"
            class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="tab" value="parameter">

            {{-- Ekstrakurikuler --}}
            <x-form.select
                label="Ekstrakurikuler"
                name="ekstra_id"
                :options="$ekstraList"
                placeholder="Pilih Ekstrakurikuler"
                :selected="old('ekstra_id', $item['ekstra_id'])"
                required
            />

            {{-- Parameter --}}
            <x-form.textarea
                label="Parameter"
                name="parameter"
                :value="old('parameter', $item['parameter'])"
                rows="3"
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
