<x-modal name="form-create-lingkup-materi" title="Tambah Lingkup Materi" maxWidth="2xl">
    <form action="{{ route('lingkup-materi.store', ['tab' => request('tab', 'lingkup-materi')]) }}" method="POST" enctype="multipart/form-data"
        class="space-y-6 sm:p-6">
        @csrf
        <input type="hidden" name="tab" value="lingkup-materi">

        {{-- Kelas --}}
        <x-form.select
            name="kelas_id"
            label="Kelas"
            id="kelas_id"
            :options="$kelasSelect"
            placeholder="Pilih Kelas"
            :selected="old('kelas_id', '')"
            required
        />

        {{-- Mapel --}}
        <x-form.select
            name="guru_kelas_id"
            label="Mapel"
            id="guru_kelas_id"
            :options="$guruKelasAll"
            placeholder="Pilih Mapel"
            :selected="old('guru_kelas_id', '')"
            required
            />

        {{-- Bab --}}
        <x-form.select
            name="bab_id"
            label="Bab"
            :options="$babSelect"
            placeholder="Pilih Bab"
            :value="old('bab_id')"
            :selected="old('bab_id', '')"
            required
        />

        {{-- Lingkup Materi --}}
        <x-form.textarea
            label="Lingkup Materi"
            name="nama"
            :value="old('nama')"
            required
        />

        {{-- Periode --}}
        <x-form.select
            name="periode"
            label="Periode"
            :options="['tengah' => 'Tengah Semester', 'akhir' => 'Akhir Semester']"
            :selected="old('periode', 'tengah')"
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

<script>
    window.addEventListener('open-modal', (e) => {
        if (e.detail === 'form-create-lingkup-materi') {
            setTimeout(() => {
                const kelasSelect = document.getElementById('kelas_id');
                const mapelSelect = document.getElementById('guru_kelas_id');
                const guruKelasAll = @json($guruKelasAll);

                const mapelTom = mapelSelect.tomselect; // akses instance TomSelect

                kelasSelect.addEventListener('change', function () {
                    const kelasId = this.value;

                    // Clear semua opsi sebelumnya
                    mapelTom.clearOptions();

                    // Tambahkan opsi default
                    mapelTom.addOption({ value: '', text: 'Pilih Mapel' });

                    // Tambahkan opsi yang sesuai kelas
                    Object.entries(guruKelasAll).forEach(([id, item]) => {
                        if (item.kelas_id == kelasId) {
                            mapelTom.addOption({ value: id, text: item.mapel });
                        }
                    });

                    // Refresh render dropdown
                    mapelTom.refreshOptions();
                });
            }, 100); // delay agar modal render sempurna dulu
        }
    });
</script>
