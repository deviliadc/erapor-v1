<x-modal name="form-create-tujuan" title="Tambah Tujuan Pembelajaran" maxWidth="2xl">
    <form action="{{ route('tujuan-pembelajaran.store') }}" method="POST" enctype="multipart/form-data"
        x-data="formTujuan()" x-init="init()" class="space-y-6 sm:p-6">
        @csrf
        <input type="hidden" name="tab" value="tujuan-pembelajaran">

        {{-- Pilih Lingkup Materi --}}
        <x-form.select
            label="Lingkup Materi"
            name="lingkup_materi_id"
            x-model="selectedLingkupMateri"
            :options="$lingkupMateriOptions"
            :selected="old('lingkup_materi_id')"
            required
        >
            <option value="" disabled selected>-- Pilih Lingkup Materi --</option>
            <option value="tambah">+ Tambah Lingkup Materi</option>
        </x-form.select>

        {{-- Jika Tambah Lingkup Materi dipilih --}}
        <div x-show="selectedLingkupMateri === 'tambah'" x-cloak class="space-y-4">
            <x-form.select
                label="Kelas & Mapel"
                name="guru_kelas_id"
                :options="$guruKelasSelect->mapWithKeys(fn($gk) => [
                    $gk->id => 'Kelas ' . optional($gk->kelas)->nama . ' - ' . optional($gk->mapel)->nama
                ])"
                :selected="old('guru_kelas_id')"
                required
            />
            <x-form.select
                label="Bab"
                name="bab_id"
                :options="$babSelect"
                :selected="old('bab_id')"
                required
            />
            <x-form.input
                label="Nama Lingkup Materi"
                name="lingkup_materi"
                :value="old('lingkup_materi')"
                required
            />
        </div>

        {{-- Subbab --}}
        <x-form.input
            label="Subbab"
            name="subbab"
            :value="old('subbab')"
        />

        {{-- Tujuan Pembelajaran --}}
        <x-form.textarea
            label="Tujuan Pembelajaran"
            name="tujuan"
            :value="old('tujuan')"
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
    document.addEventListener('alpine:init', () => {
        Alpine.data('formTujuan', () => ({
            selectedLingkupMateri: @json(old('lingkup_materi_id', '')),
            init() {
                const selectEl = this.$el.querySelector('[name="lingkup_materi_id"]');
                if (selectEl) {
                    this.selectedLingkupMateri = selectEl.value;

                    selectEl.addEventListener('change', () => {
                        this.selectedLingkupMateri = selectEl.value;
                    });
                }
            }
        }));
    });
</script>
