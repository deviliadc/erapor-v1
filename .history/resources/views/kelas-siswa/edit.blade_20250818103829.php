@foreach ($kelasList as $item)
    <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit Kelas" maxWidth="2xl">
        {{-- <form action="{{ role_route('kelas-siswa.update', ['kelas' => $item['id'], 'siswa' => $item['id']]) }}" method="POST" class="space-y-6 sm:p-6"> --}}
        <form action="{{ role_route('kelas-siswa.update', ['kelas_siswa' => $item['id']]) }}" method="POST"
            class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')

            {{-- Kelas --}}
            {{-- <x-form.select
                name="kelas_id"
                label="Kelas"
                :options="$kelasList"
                placeholder="Pilih Kelas"
                :selected="$item['kelas_id']"
                :searchable="true"
                readonly
            /> --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Kelas
                </label>
                                <div
                    class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                    {{ $item['kelas'] }}
                </div>
            </div>

            {{-- Guru Wali --}}
            <x-form.select
                name="guru_id"
                label="Guru Wali"
                :options="$guruList"
                placeholder="Pilih Guru Wali"
                :selected="$item['guru_id']"
                :searchable="true"
                required
            />

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>
@endforeach
