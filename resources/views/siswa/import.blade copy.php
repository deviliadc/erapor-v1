<x-modal name="import-siswa" title="Import Data Siswa">
    <div class="p-4 space-y-4">
        <div class="flex items-center gap-5 mb-4">
            <a href="{{ role_route('siswa.template') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs ring-1 ring-inset ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                </svg>
                Download Template Excel
            </a>
        </div>

        {{-- Form Upload Drag & Drop + Input Manual --}}
        <form id="import-dropzone" action="{{ role_route('siswa.import') }}" method="POST" enctype="multipart/form-data"
            class="dropzone rounded-xl border border-dashed border-gray-300 bg-gray-50 p-7 lg:p-10 dark:border-gray-700 dark:bg-gray-900 text-center">
            @csrf
            <div class="dz-message m-0">
                <div class="mb-[22px] flex justify-center">
                    <div
                        class="flex h-[68px] w-[68px] items-center justify-center rounded-full bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-400">
                        <svg class="fill-current" width="29" height="28" viewBox="0 0 29 28" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M14.5019 3.91699C14.2852 3.91699 14.0899 4.00891 13.953 4.15589L8.57363 9.53186C8.28065 9.82466 8.2805 10.2995 8.5733 10.5925C8.8661 10.8855 9.34097 10.8857 9.63396 10.5929L13.7519 6.47752V18.667C13.7519 19.0812 14.0877 19.417 14.5019 19.417C14.9161 19.417 15.2519 19.0812 15.2519 18.667V6.48234L19.3653 10.5929C19.6583 10.8857 20.1332 10.8855 20.426 10.5925C20.7188 10.2995 20.7186 9.82463 20.4256 9.53184L15.0838 4.19378C14.9463 4.02488 14.7367 3.91699 14.5019 3.91699ZM5.91626 18.667C5.91626 18.2528 5.58047 17.917 5.16626 17.917C4.75205 17.917 4.41626 18.2528 4.41626 18.667V21.8337C4.41626 23.0763 5.42362 24.0837 6.66626 24.0837H22.3339C23.5766 24.0837 24.5839 23.0763 24.5839 21.8337V18.667C24.5839 18.2528 24.2482 17.917 23.8339 17.917C23.4197 17.917 23.0839 18.2528 23.0839 18.667V21.8337C23.0839 22.2479 22.7482 22.5837 22.3339 22.5837H6.66626C6.25205 22.5837 5.91626 22.2479 5.91626 21.8337V18.667Z"
                                fill=""></path>
                        </svg>
                    </div>
                </div>
                <h4 class="text-theme-xl mb-3 font-semibold text-gray-800 dark:text-white/90">
                    Drag & Drop File Here
                </h4>
                <span class="mx-auto mb-5 block w-full max-w-[290px] text-sm text-gray-700 dark:text-gray-400">
                    Drag and drop your Excel file here atau klik tombol di bawah untuk memilih file manual.
                </span>
                <label class="text-theme-sm text-brand-500 font-medium underline cursor-pointer block mt-2">
                    Browse File
                    <input type="file" name="file" accept=".xlsx" required class="hidden"
                        id="manual-file-input" />
                </label>
            </div>
        </form>

        {{-- <div class="flex justify-center gap-3 mt-6">
            <button id="btn-upload" type="button"
                class="inline-flex items-center px-4 py-2 bg-brand-500 text-white rounded-lg hover:bg-brand-600 font-medium"
                disabled>
                <span id="btn-upload-text">Upload & Simpan</span>
            </button>
            <button type="button" onclick="Dropzone.forElement('#import-dropzone').removeAllFiles(true)"
                class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                Batal
            </button>
        </div> --}}
    </div>
</x-modal>
