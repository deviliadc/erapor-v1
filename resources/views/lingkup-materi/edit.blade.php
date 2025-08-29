@foreach ($lingkupMateri as $item)
    <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit Lingkup Materi" maxWidth="2xl">
        <form action="{{ role_route('lingkup-materi.update',['lingkup_materi' => $item['id']], ['tab' => request('tab', 'lingkup-materi')]) }}" method="POST"
            class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="tab" value="lingkup-materi">

            {{-- Kelas --}}
            <x-form.select
                name="kelas_id"
                label="Kelas"
                id="kelasSelectEdit-{{ $item['id'] }}"
                :options="$kelasSelect"
                :selected="$item['kelas_id'] ?? ''"
                placeholder="Pilih Kelas"
                required
                data-id="{{ $item['id'] }}"
            />

            {{-- Mapel --}}
            {{-- @php
                $mapelOptions = [];
                foreach ($guruKelasAll as $gkId => $data) {
                    if ($data['kelas_id'] == $item['kelas_id']) {
                        $mapelOptions[$gkId] = $data['mapel'];
                    }
                }
            @endphp --}}

            {{-- Mapel --}}
            {{-- <x-form.select
                name="guru_kelas_id"
                label="Mapel"
                id="mapelSelectEdit-{{ $item['id'] }}"
                :options="$mapelOptions"
                :selected="old('guru_kelas_id', $item['guru_kelas_id'])"
                placeholder="Pilih Mapel"
                required
                data-id="{{ $item['id'] }}"
                data-selected="{{ $item['guru_kelas_id'] }}"
            /> --}}
            <x-form.select
                name="mapel_id"
                label="Mapel"
                id="mapelSelectEdit-{{ $item['id'] }}"
                :options="$mapelSelect"
                :selected="old('mapel_id', $item['mapel_id'])"
                placeholder="Pilih Mapel"
                required
            />

            {{-- Bab --}}
            <x-form.select
                name="bab_id"
                label="Bab"
                :options="$babSelect"
                placeholder="Pilih Bab"
                :selected="$item['bab_id']"
                required
            />

            {{-- Lingkup Materi --}}
            <x-form.textarea
                label="Lingkup Materi"
                name="nama"
                :value="old('nama', $item['nama'])"
                required
            />

            {{-- Periode --}}
            <x-form.select
                name="periode"
                label="Periode"
                :options="['tengah' => 'Tengah Semester', 'akhir' => 'Akhir Semester']"
                :selected="$item['periode'] ?? 'tengah'"
                required
            />

            {{-- Semester --}}
            <x-form.select
                name="semester"
                label="Semester"
                :options="['genap' => 'Genap', 'ganjil' => 'Ganjil']"
                :selected="$item['semester'] ?? 'genap'"
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
