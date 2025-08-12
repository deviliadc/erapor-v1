<x-modal name="form-create-parameter" title="Tambah Parameter Ekstrakurikuler" maxWidth="2xl">
    <form action="{{ role_route('param-ekstra.store', ['tab' => request('tab', 'parameter')]) }}" method="POST" enctype="multipart/form-data"
        class="space-y-6 sm:p-6">
        @csrf
        <input type="hidden" name="tab" value="parameter">

        {{-- Ekstrakurikuler --}}
        <x-form.select
            label="Ekstrakurikuler"
            name="ekstra_id"
            :options="$ekstraList"
            placeholder="Pilih Ekstrakurikuler"
            :selected="old('ekstra_id')"
            required
        />

        {{-- Parameter --}}
        <x-form.textarea
            label="Parameter"
            name="parameter"
            :value="old('parameter')"
            rows="3"
            required
        />

        {{-- Tombol Submit --}}
        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                Tambah
            </button>
        </div>
    </form>
</x-modal>
