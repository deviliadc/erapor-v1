@foreach ($tahunAjaran as $item)
    <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit Tahun Pelajaran" maxWidth="2xl">
        <form action="{{ role_route('tahun-ajaran.update', ['tahun_ajaran' => $item['id']]) }}" method="POST" class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')
            {{-- Tahun --}}
            <x-form.input name="tahun" label="Tahun Ajaran" :value="old('tahun', $item['tahun'])" required />

            {{-- Mulai --}}
            <x-form.date name="tahun_mulai" label="Tanggal Mulai" type="date" :value="old('tahun_mulai', $item['tahun_mulai'])" required />

            {{-- Selesai --}}
            <x-form.input name="tahun_selesai" label="Tanggal Selesai" type="date" :value="old('tahun_selesai', $item['tahun_selesai'])" required />

            {{-- Status --}}
            <div x-data="{ isActive: {{ old('is_active', $item['tahun_status']) ? 'true' : 'false' }} }" @click.stop @mousedown.stop>
                <input type="hidden" name="is_active" value="0">
                <label for="is_active_{{ $item['id'] }}"
                    class="flex items-center text-sm font-medium text-gray-700 cursor-pointer select-none dark:text-gray-400"
                    @click.stop @mousedown.stop>
                    <div class="relative">
                        <input type="checkbox" id="is_active_{{ $item['id'] }}" name="is_active" value="1"
                            class="sr-only" @click.stop @mousedown.stop @change="isActive = !isActive"
                            :checked="isActive">
                        <div :class="isActive ? 'border-brand-500 bg-brand-500' :
                            'bg-transparent border-gray-300 dark:border-gray-700'"
                            class="mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] transition-colors duration-200">
                            <span :class="isActive ? 'opacity-100' : 'opacity-0'"
                                class="transition-opacity duration-150">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white"
                                        stroke-width="1.94437" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    Aktifkan Tahun & Semester ini <span class="text-error-500">*</span>
                </label>
                @error('is_active')
                    <div class="mt-1 text-sm text-red-600 dark:text-red-400">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            {{-- Tombol Submit --}}
            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>
@endforeach
