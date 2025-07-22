<x-modal name="form-create-p5-proyek" title="Tambah P5 Proyek" maxWidth="2xl">
    <form action="{{ route('p5-proyek.store', ['tab' => request('tab', 'proyek')]) }}" method="POST"
        enctype="multipart/form-data" class="space-y-6 sm:p-6">
        @csrf
        {{-- <input type="hidden" name="tab" value="{{ request('tab', 'dimensi') }}"> --}}
        <input type="hidden" name="tab" value="proyek">

<!-- Section 1: Info Proyek -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <x-form.input label="Nama Proyek" name="nama_proyek" :value="old('nama_proyek')" required />
        <x-form.select label="Tema" name="p5_tema_id" :options="$temaList" placeholder="Pilih Tema" :selected="old('p5_tema_id')" required />
        <x-form.select label="Tahun Semester" name="tahun_semester_id" :options="$tahunSemesterList" placeholder="Pilih Tahun Semester" :selected="old('tahun_semester_id')" required />
        <x-form.select label="Kelas" name="kelas_id" :options="$kelasList" placeholder="Pilih Kelas" :selected="old('kelas_id')" required />
        <x-form.select label="Guru Pembimbing" name="guru_id" :options="$guruList" placeholder="Pilih Guru Pembimbing" :selected="old('guru_id')" required />
    </div>

    <!-- Section 2: Dimensi & Sub Elemen -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <x-form.select
            label="Dimensi (Pilih max 3)"
            name="dimensi_id[]"
            :options="$dimensiList"
            :selected="old('dimensi_id', [])"
            multiple
            required
            id="dimensi-select" />

        <x-form.select
            label="Sub Elemen (Pilih 2–6)"
            name="sub_elemen_id[]"
            :options="[]"
            :selected="old('sub_elemen_id', [])"
            multiple
            required
            id="sub-elemen-select" />
    </div>

    <!-- Deskripsi -->
    <x-form.textarea label="Deskripsi" name="deskripsi_proyek" :value="old('deskripsi_proyek')" rows="3" />


        {{-- Nama Proyek --}}
        <x-form.input label="Nama Proyek" name="nama_proyek" :value="old('nama_proyek')" required />

        {{-- Tema --}}
        <x-form.select label="Tema" name="p5_tema_id" :options="$temaList" placeholder="Pilih Tema" :selected="old('p5_tema_id')"
            required />

        {{-- Dimensi --}}
        {{-- <x-form.select label="Dimensi" name="dimensi_id[]" multiple
            :options="$dimensiList"
            placeholder="Pilih Dimensi"
            :selected="old('dimensi_id', [])" required /> --}}
        <x-form.select label="Dimensi" name="dimensi_id[]" multiple :options="$dimensiList" placeholder="Pilih Dimensi"
            :selected="request('dimensi_id', old('dimensi_id', []))" required id="dimensi-select" />

        <x-form.select label="Sub Elemen" name="sub_elemen_id[]" :options="[]" {{-- kosong, akan diisi JS --}}
            placeholder="Pilih Sub Elemen" :selected="old('sub_elemen_id', [])" required id="sub-elemen-select" />

        {{-- Tahun Semester --}}
        <x-form.select label="Tahun Semester" name="tahun_semester_id" :options="$tahunSemesterList"
            placeholder="Pilih Tahun Semester" :selected="old('tahun_semester_id')" required />

        {{-- Kelas --}}
        <x-form.select label="Kelas" name="kelas_id" :options="$kelasList" placeholder="Pilih Kelas" :selected="old('kelas_id')"
            required />

        {{-- Guru Pembimbing --}}
        <x-form.select label="Guru Pembimbing" name="guru_id" :options="$guruList" placeholder="Pilih Guru Pembimbing"
            :selected="old('guru_id')" required />

        {{-- Deskripsi --}}
        <x-form.textarea label="Deskripsi" name="deskripsi_proyek" :value="old('deskripsi_proyek')" rows="3" />

        {{-- Tombol Submit --}}
        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                Tambah
            </button>
        </div>
    </form>
</x-modal>

{{-- <script>
    const dimensiToSubElemen = @json($dimensiToSubElemen);
    const dimensiSelect = document.getElementById('dimensi-select');
    const subElemenSelect = document.getElementById('sub-elemen-select');
    const selectedSub = @json(old('sub_elemen_id', []));

    function updateSubElemenOptions() {
        let selectedDimensi = Array.from(dimensiSelect.selectedOptions).map(opt => opt.value);
        let subOptions = {};
        selectedDimensi.forEach(dimId => {
            if (dimensiToSubElemen[dimId]) {
                Object.entries(dimensiToSubElemen[dimId]).forEach(([id, nama]) => {
                    subOptions[id] = nama;
                });
            }
        });

        // Kosongkan dan isi ulang select sub elemen
        subElemenSelect.innerHTML = '';
        Object.entries(subOptions).forEach(([id, nama]) => {
            let opt = document.createElement('option');
            opt.value = id;
            opt.textContent = nama;
            if (selectedSub.includes(id) || selectedSub.includes(Number(id))) opt.selected = true;
            subElemenSelect.appendChild(opt);
        });
    }

    // Inisialisasi saat halaman load
    updateSubElemenOptions();

    // Update saat dimensi berubah
    dimensiSelect.addEventListener('change', function() {
        updateSubElemenOptions();
    });
</script> --}}

{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        const dimensiToSubElemen = @json($dimensiToSubElemen);
        const dimensiSelect = document.getElementById('dimensi-select');
        const subElemenSelect = document.getElementById('sub-elemen-select');
        const selectedSub = @json(old('sub_elemen_id', []));

        function updateSubElemenOptions() {
            const selectedDimensi = Array.from(dimensiSelect.selectedOptions).map(opt => opt.value);
            const subOptions = {};

            // Kumpulkan sub elemen dari semua dimensi yang dipilih
            selectedDimensi.forEach(dimId => {
                const sub = dimensiToSubElemen[dimId] || {};
                Object.entries(sub).forEach(([id, nama]) => {
                    subOptions[id] = nama;
                });
            });

            // Hapus semua option sub elemen
            subElemenSelect.innerHTML = '';

            // Jika ada sub elemen, tampilkan
            if (Object.keys(subOptions).length > 0) {
                Object.entries(subOptions).forEach(([id, nama]) => {
                    const opt = document.createElement('option');
                    opt.value = id;
                    opt.textContent = nama;
                    if (selectedSub.includes(id) || selectedSub.includes(Number(id))) {
                        opt.selected = true;
                    }
                    subElemenSelect.appendChild(opt);
                });
            } else {
                // Jika tidak ada sub elemen tersedia
                const opt = document.createElement('option');
                opt.disabled = true;
                opt.textContent = 'Tidak ada Sub Elemen tersedia';
                subElemenSelect.appendChild(opt);
            }
        }

        // Jalankan saat halaman pertama kali load
        updateSubElemenOptions();

        // Jalankan saat dimensi berubah
        dimensiSelect.addEventListener('change', updateSubElemenOptions);
    });
</script> --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const dimensiToSubElemen = @json($dimensiToSubElemen);
    const dimensiSelect = document.getElementById('dimensi-select');
    const subElemenSelect = document.getElementById('sub-elemen-select');
    const selectedSub = @json(old('sub_elemen_id', []));

    function updateSubElemenOptions() {
        const selectedDimensi = Array.from(dimensiSelect.selectedOptions).map(opt => opt.value);
        const subOptions = {};

        selectedDimensi.forEach(dimId => {
            const sub = dimensiToSubElemen[dimId.toString()] || {}; // PAKAI toString()
            Object.entries(sub).forEach(([id, nama]) => {
                subOptions[id] = nama;
            });
        });

        subElemenSelect.innerHTML = '';

        if (Object.keys(subOptions).length > 0) {
            Object.entries(subOptions).forEach(([id, nama]) => {
                const opt = document.createElement('option');
                opt.value = id;
                opt.textContent = nama;
                if (selectedSub.includes(id) || selectedSub.includes(Number(id))) {
                    opt.selected = true;
                }
                subElemenSelect.appendChild(opt);
            });
        } else {
            const opt = document.createElement('option');
            opt.disabled = true;
            opt.textContent = 'Tidak ada Sub Elemen tersedia';
            subElemenSelect.appendChild(opt);
        }
    }

    updateSubElemenOptions();
    dimensiSelect.addEventListener('change', updateSubElemenOptions);
});

</script>
