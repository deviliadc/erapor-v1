<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-5 py-4 sm:px-6 sm:py-5">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Create Tujuan Pembelajaran</h3>
            </div>

            <form action="{{ route('admin.tujuan-pembelajaran.store') }}" method="POST"
                class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800" x-data="{
                    lingkup: @json($lingkupMateri->keyBy('id')),
                    selected: '{{ old('lingkup_materi_id') }}',
                    get current() {
                        return this.lingkup[this.selected] ?? { kelas: {}, mapel: {}, bab: {} };
                    }
                }">
                @csrf

                {{-- Dropdown Lingkup Materi --}}
   <x-form.select2 label="Lingkup Materi" name="lingkup_materi_id" :options="$lingkupMateri
        ->mapWithKeys(fn($l) => [
            $l->id => $l->lingkup_materi . ' ( Kelas ' . ($l->kelas->nama ?? '-') . ' - ' . ($l->mapel->nama ?? '-') . ' - ' . ($l->bab->nama ?? '-') . ')',
        ])->prepend('+ Tambah Lingkup Materi', 'tambah')" :selected="old('lingkup_materi_id')" x-model="selected" />


                <x-form.select2 name="lingkup_materi_id" onchange="toggleLingkupForm(this.value)">
    <option value="">Pilih Lingkup Materi</option>
    @foreach ($lingkupMateri as $l)
        <option value="{{ $l->id }}" {{ old('lingkup_materi_id') == $l->id ? 'selected' : '' }}>
            {{ $l->lingkup_materi }} (Kelas {{ $l->kelas->nama ?? '-' }} - {{ $l->mapel->nama ?? '-' }} - {{ $l->bab->nama ?? '-' }})
        </option>
    @endforeach
    <option value="tambah" {{ old('lingkup_materi_id') == 'tambah' ? 'selected' : '' }}>+ Tambah Lingkup Baru</option>
</x-form.select2>

                {{-- Form Tambah Lingkup Materi --}}
                <div x-show="selected === 'tambah'" x-transition
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <x-form.select name="kelas_id" label="Kelas" :options="$kelas" placeholder="Pilih Kelas" />
                    <x-form.select name="mapel_id" label="Mapel" :options="$mapel" placeholder="Pilih Mapel" />
                    <x-form.select name="bab_id" label="Bab" :options="$bab" placeholder="Pilih Bab" />
                    <x-form.input name="lingkup_materi" label="Lingkup Materi" :value="old('lingkup_materi')" />
                </div>

                {{-- Subbab --}}
                <x-form.input label="Subbab" name="subbab" :value="old('subbab')" />

                {{-- Tujuan Pembelajaran --}}
                <x-form.input label="Tujuan Pembelajaran" name="tujuan" :value="old('tujuan')" />

                {{-- Submit --}}
                <div>
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                        Tambah
                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M9.25 5C9.25 4.59 9.59 4.25 10 4.25C10.41 4.25 10.75 4.59 10.75 5V9.25H15C15.41 9.25 15.75 9.59 15.75 10C15.75 10.41 15.41 10.75 15 10.75H10.75V15C10.75 15.41 10.41 15.75 10 15.75C9.59 15.75 9.25 15.41 9.25 15V10.75H5C4.59 10.75 4.25 10.41 4.25 10C4.25 9.59 4.59 9.25 5 9.25H9.25V5Z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
{{--
<script>
    function toggleLingkupForm(value) {
        const formTambah = document.getElementById('form_tambah_lingkup');
        formTambah.style.display = (value === 'tambah') ? 'block' : 'none';
    }
</script> --}}
{{--
<script>
    function dropdownData() {
        return {
            selectedLingkup: '',
            lingkupList: [],
            detailLingkup: {
                kelas: '',
                mapel: '',
                bab: '',
            },

            fetchLingkup() {
                fetch('/get-all-lingkup') // endpoint ini mengembalikan list seluruh lingkup
                    .then(res => res.json())
                    .then(data => this.lingkupList = data);
            },

            setDetailLingkup() {
                const selected = this.lingkupList.find(l => l.id == this.selectedLingkup);
                if (selected) {
                    this.detailLingkup.kelas = selected.kelas?.nama || '';
                    this.detailLingkup.mapel = selected.mapel?.nama || '';
                    this.detailLingkup.bab = selected.bab?.nama || '';
                }
            }
        }
    }
</script> --}}
