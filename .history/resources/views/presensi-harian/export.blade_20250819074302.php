{{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\presensi-harian\export.blade.php --}}
<x-modal name="export-presensi-harian" title="Export Data Presensi Harian">
    <form action="{{ role_route('presensi-harian.export') }}" method="GET" target="_blank" class="space-y-4">
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
            <div>
                <label for="tanggal_awal" class="font-semibold mb-2 block">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" id="tanggal_awal" required class="form-input w-full" />
            </div>
            <div>
                <label for="tanggal_akhir" class="font-semibold mb-2 block">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" id="tanggal_akhir" required class="form-input w-full" />
            </div>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="btn btn-brand">Export Excel</button>
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
</x-modal>
