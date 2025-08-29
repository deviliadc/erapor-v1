{{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\kelas-siswa\promote-global.blade.php --}}
<x-modal name="form-promote-global" title="Promosikan Siswa per Kelas" maxWidth="2xl">
    <form action="{{ role_route('kelas-siswa.promoteGlobal') }}" method="POST" class="space-y-6 sm:p-6">
        @csrf
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700">Pilih Kelas yang Akan Dinaikkan</label>
            <div class="grid grid-cols-2 gap-2">
                @foreach($kelasList as $kelas)
                    <label class="flex items-center text-sm font-medium text-gray-700 cursor-pointer select-none dark:text-gray-400 space-x-2"
                        x-data="{ selected: false }">
                        <div class="relative">
                            <input type="checkbox"
                                id="kelas_{{ $kelas->id }}"
                                name="kelas_id[]"
                                value="{{ $kelas->id }}"
                                class="sr-only"
                                @change="selected = !selected"
                                :checked="selected">

                            <div :class="selected ? 'border-brand-500 bg-brand-500' : 'bg-transparent border-gray-300 dark:border-gray-700'"
                                class="flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] transition-colors duration-200">
                                <span :class="selected ? 'opacity-100' : 'opacity-0'" class="transition-opacity duration-150">
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7"
                                            stroke="white" stroke-width="1.94437"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="p-1"><span class="select-none">{{ $kelas->nama }}</span></div>
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
