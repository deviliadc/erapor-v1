{{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\presensi-harian\export.blade.php --}}
<x-modal name="export-presensi-harian" title="Export Data Presensi Harian">
    <form action="{{ role_route('presensi-harian.export') }}" method="GET" target="_blank" class="space-y-4">
        <div class="p-4 space-y-4">
        <div>
            <label class="font-semibold mb-2 block">Pilih Kelas</label>
            <div class="flex flex-col gap-2">
                <label>
                    <input type="checkbox" id="check-all-kelas" /> <span>Pilih Semua</span>
                </label>
                @foreach ($kelasList as $kelas)
                    <label>
                        <input type="checkbox" name="kelas_id[]" value="{{ $kelas->id }}" class="checkbox-kelas" />
                        {{ $kelas->nama }}
                    </label>
                @endforeach
            </div>
        </div>
        <div class="flex gap-4">
            <x-form.date-picker name="tanggal_awal" label="Tanggal Awal" required />
            <x-form.date-picker name="tanggal_akhir" label="Tanggal Akhir" required />

            {{-- <div>
                <label for="tanggal_awal" class="font-semibold mb-2 block">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" id="tanggal_awal" required class="form-input w-full" />
            </div>
            <div>
                <label for="tanggal_akhir" class="font-semibold mb-2 block">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" id="tanggal_akhir" required class="form-input w-full" />
            </div> --}}
        </div>
        <div class="flex justify-end pt-6">
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                Export
            </button>
        </div>
    </form>
    <script>
        // Pilih semua kelas
        document.addEventListener('DOMContentLoaded', function () {
            const checkAll = document.getElementById('check-all-kelas');
            const kelasCheckboxes = document.querySelectorAll('.checkbox-kelas');
            if (checkAll) {
                checkAll.addEventListener('change', function () {
                    kelasCheckboxes.forEach(cb => cb.checked = checkAll.checked);
                });
            }
        });
    </script>
    </div>
</x-modal>
