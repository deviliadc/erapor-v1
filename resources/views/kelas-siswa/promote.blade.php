{{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\kelas-siswa\promote-global.blade.php --}}
<x-modal name="form-promote-global" title="Promosikan Siswa per Kelas" maxWidth="2xl">
    <form action="{{ role_route('kelas-siswa.promoteGlobal') }}" method="POST" class="space-y-6 sm:p-6">
        @csrf
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700">Pilih Kelas yang Akan Dinaikkan</label>
            <div class="grid grid-cols-2 gap-2">
                @foreach($kelasList as $kelas)
                    <label class="flex items-center">
                        <input type="checkbox" name="kelas_id[]" value="{{ $kelas->id }}">
                        <span class="ml-2">{{ $kelas->nama }}</span>
                    </label>
                @endforeach
            </div>
        </div>
        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                Promote
            </button>
        </div>
    </form>
</x-modal>
