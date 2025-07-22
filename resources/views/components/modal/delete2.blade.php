@props([
    'id',
    'route',
    'title' => 'Apakah Anda yakin?',
    'message' => 'Tindakan ini tidak dapat dibatalkan.',
])

<x-modal name="delete-modal-{{ $id }}" maxWidth="md">
    <div class="p-6 text-center">
        <!-- Icon -->
        <div class="relative mx-auto mb-6 w-20 h-20">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                class="w-full h-full text-error-500 fill-current">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75
                    9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72
                    6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72
                    1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72
                    1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75
                    0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" />
            </svg>
        </div>

        <!-- Teks -->
        <h2 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white">{{ $title }}</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $message }}</p>

        <!-- Tombol Aksi -->
        <div class="mt-6 flex justify-center gap-3">
            <button @click="$dispatch('close-modal', { detail: 'delete-modal-{{ $id }}' })"
                class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow ring-1 ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                Batal
            </button>
            <form method="POST" action="{{ $route }}">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-error-500 hover:bg-error-600 shadow">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</x-modal>
