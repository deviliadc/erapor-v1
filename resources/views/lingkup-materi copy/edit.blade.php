@foreach ($lingkup_materi as $item)
    <x-modal name="edit-modal-{{ $item->id }}" title="Edit Lingkup Materi" maxWidth="2xl">
        <form action="{{ role_route('lingkup-materi.update', ['lingkup_materi' => $item->id]) }}" method="POST"
            class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')

            {{-- Kelas --}}
            <x-form.select
                name="kelas_id"
                label="Kelas"
                id="kelasSelectEdit-{{ $item->id }}"
                :options="$kelas"
                :selected="$item->guruKelas->kelas_id ?? ''"
                placeholder="Pilih Kelas"
                required
                data-id="{{ $item->id }}"
            />

            {{-- Mapel --}}
            <x-form.select
                name="guru_kelas_id"
                label="Mapel"
                id="mapelSelectEdit-{{ $item->id }}"
                :options="[]" {{-- options diisi lewat JS --}}
                :selected="$item->guru_kelas_id"
                placeholder="Pilih Mapel"
                required
                data-id="{{ $item->id }}"
                data-selected="{{ $item->guru_kelas_id }}"
            />

            {{-- Bab --}}
            <x-form.select
                name="bab_id"
                label="Bab"
                :options="$bab"
                placeholder="Pilih Bab"
                :selected="$item->bab_id"
                required
            />

            {{-- Lingkup Materi --}}
            <x-form.textarea label="Lingkup Materi" name="nama" :value="old('nama', $item->nama)" required />

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const guruKelasAll = @json($guruKelasAll);

        document.querySelectorAll('[id^="kelasSelectEdit-"]').forEach(kelasSelect => {
            const id = kelasSelect.dataset.id;
            const mapelSelect = document.getElementById(`mapelSelectEdit-${id}`);

            // Ambil value kelas dan mapel yang tersimpan
            const selectedKelasId = kelasSelect.value;
            const selectedGuruKelasId = mapelSelect.getAttribute('data-selected');

            // Debug
            console.log('selectedKelasId:', selectedKelasId, 'selectedGuruKelasId:', selectedGuruKelasId, guruKelasAll);

            function fillMapelOptions(kelasId, selectedId = null) {
                let options = `<option value="">Pilih Mapel</option>`;
                Object.entries(guruKelasAll).forEach(([gkId, item]) => {
                    if (String(item.kelas_id) === String(kelasId)) {
                        options += `<option value="${gkId}" ${String(selectedId) === String(gkId) ? 'selected' : ''}>${item.mapel}</option>`;
                    }
                });
                mapelSelect.innerHTML = options;
            }

            // Isi mapel saat pertama kali modal dibuka
            if (selectedKelasId) {
                fillMapelOptions(selectedKelasId, selectedGuruKelasId);
            } else {
                mapelSelect.innerHTML = `<option value="">Pilih Mapel</option>`;
            }

            // Saat kelas diganti
            kelasSelect.addEventListener('change', function () {
                fillMapelOptions(this.value);
            });
        });
    });
</script>
