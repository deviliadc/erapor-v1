@props(['id', 'title' => 'Apakah Anda yakin?', 'message' => 'Tindakan ini tidak dapat dibatalkan.', 'route'])

<div x-show="isDeleteModalOpen{{ $id }}" x-cloak x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-gray-400/50 backdrop-blur-md overflow-y-auto">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm"></div>

    <!-- Modal -->
    <div @click.outside="isDeleteModalOpen{{ $id }} = false"
        class="relative w-full max-w-md mx-auto bg-white dark:bg-gray-900 rounded-2xl shadow-lg overflow-hidden z-50">
        <!-- Tombol Close -->
        <button @click="isDeleteModalOpen{{ $id }} = false"
            class="absolute right-4 top-4 z-10 pt-4 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24">
                <path d="M6 18L18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>

        <!-- Isi Modal -->
        <div class="p-6 sm:p-8 text-center">
            <div class="mx-auto mb-6 w-20 h-20 flex items-center justify-center">
                <svg class="w-full h-full text-error-500" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75
                        9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72
                        6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72
                        1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72
                        1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75
                        0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" />
                </svg>
            </div>

            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-2">{{ $title }}</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $message }}</p>

            <div class="mt-6 flex justify-center gap-3">
                <button @click="isDeleteModalOpen{{ $id }} = false"
                    class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow ring-1 ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                    Batal
                </button>

                <form method="POST" action="{{ $route }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                    {{-- class="inline-flex items-center gap-2 rounded-lg bg-error-500 px-4 py-2.5 text-sm font-medium text-white shadow hover:bg-error-600 dark:bg-error-800 dark:text-white dark:hover:bg-error-700"> --}}
                    class="flex w-full sm:w-auto justify-center rounded-lg bg-error-500 px-4 py-3 text-sm font-medium text-white shadow-theme-xs hover:bg-error-600">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
