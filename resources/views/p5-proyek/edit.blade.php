@foreach ($proyek as $item)
    <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit P5 Proyek" maxWidth="2xl">
        <form action="{{ route('p5-proyek.update', ['p5_proyek' => $item['id']], ['tab' => request('tab', 'proyek')]) }}" method="POST"
            class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')
            {{-- <input type="hidden" name="tab" value="{{ request('tab', 'dimensi') }}"> --}}
            <input type="hidden" name="tab" value="proyek">

            

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>
@endforeach
