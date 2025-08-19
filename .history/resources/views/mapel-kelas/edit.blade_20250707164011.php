@foreach ($mapelList as $item)
    <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit Mapel" maxWidth="2xl">
        <form action="{{ route('kelas-mapel.update', ['kelas' => $kelas->id, 'mapel' => $item['id']]) }}" method="POST" class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')
            

            {{-- Mapel --}}
            <x-form.select
                name="mapel_id"
                label="Mapel"
                :options="$mapelOptions"
                placeholder="Pilih Mapel"
                :selected="$item['mapel_id']"
                :searchable="true"
                required
            />

            {{-- Guru Pengajar --}}
            <x-form.select
                name="guru_id"
                label="Guru Pengajar"
                :options="$guruOptions"
                placeholder="Pilih Guru Pengajar"
                :selected="$item['guru_id']"
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
