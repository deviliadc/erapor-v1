<x-modal name="import-siswa" title="Import Data Siswa">
    <div class="p-4 space-y-4">

        {{-- Download Template --}}
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

        {{-- Form Upload --}}
        <form id="import-dropzone" action="{{ role_route('siswa.import') }}" method="POST"
            enctype="multipart/form-data"
            class="dropzone rounded-xl border border-dashed border-gray-300 bg-gray-50 p-7 lg:p-10 dark:border-gray-700 dark:bg-gray-900 text-center">
            @csrf

            <div class="dz-message" data-dz-message>
                <h4 class="text-theme-xl mb-3 font-semibold text-gray-800 dark:text-white/90">
                    Drag & Drop File Here
                </h4>
                <span class="mx-auto mb-5 block w-full max-w-[290px] text-sm text-gray-700 dark:text-gray-400">
                    Drag & drop file Excel atau klik tombol di bawah untuk memilih file manual.
                </span>

                {{-- hidden input untuk trigger --}}
                <input type="file" id="hidden-file-input" class="hidden" accept=".xlsx,.xls,.csv" />

                <button type="button" id="browse-btn"
                    class="text-theme-sm text-brand-500 font-medium underline cursor-pointer">
                    Browse File
                </button>
            </div>

            {{-- Container Preview --}}
            <div id="dz-previews" class="mt-4"></div>
        </form>

        {{-- Tombol Submit di luar form --}}
        <div class="mt-6 flex justify-end">
            <button type="button" id="upload-btn"
                class="px-4 py-2 bg-brand-500 text-white rounded-lg hover:bg-brand-600 focus:ring-2 focus:ring-brand-400">
                Upload & Import
            </button>
        </div>
    </div>

    @push('scripts')
        <script>
            Dropzone.autoDiscover = false;

            document.addEventListener("DOMContentLoaded", function() {
                const hiddenFileInput = document.querySelector("#hidden-file-input");

                const myDropzone = new Dropzone("#import-dropzone", {
                    autoProcessQueue: false,
                    clickable: hiddenFileInput,
                    paramName: "file",
                    maxFiles: 1,
                    acceptedFiles: ".xlsx,.xls,.csv",
                    previewsContainer: "#dz-previews",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    previewTemplate: `
            <div class="dz-preview dz-file-preview flex items-center gap-4 p-3 border rounded-lg bg-white dark:bg-gray-800">
                <div class="flex-shrink-0">
                    <svg class="w-10 h-10 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4 2h14l4 4v16a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200" data-dz-name></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400" data-dz-size></p>
                    <div class="w-full bg-gray-200 rounded h-2 mt-2">
                        <div class="bg-green-500 h-2 rounded" data-dz-uploadprogress></div>
                    </div>
                    <strong class="text-red-500 text-xs" data-dz-errormessage></strong>
                </div>
                <button data-dz-remove class="text-red-500 hover:text-red-700 text-sm font-medium">Hapus</button>
            </div>
        `
                });

                // tombol browse → buka file input
                document.querySelector("#browse-btn").addEventListener("click", function() {
                    hiddenFileInput.click();
                });

                // jika file dipilih via hidden input, tambahkan ke Dropzone
                hiddenFileInput.addEventListener("change", function() {
                    if (hiddenFileInput.files.length) {
                        myDropzone.removeAllFiles(true); // hapus file sebelumnya
                        for (let file of hiddenFileInput.files) {
                            myDropzone.addFile(file);
                        }
                    }
                });

                myDropzone.on("addedfile", function() {
                    document.querySelector(".dz-message").style.display = "none";
                });

                myDropzone.on("removedfile", function() {
                    if (myDropzone.getAcceptedFiles().length === 0) {
                        document.querySelector(".dz-message").style.display = "";
                    }
                });

                document.querySelector("#upload-btn").addEventListener("click", function() {
                    if (myDropzone.getAcceptedFiles().length === 0) {
                        alert("Pilih file Excel terlebih dahulu.");
                        return;
                    }
                    myDropzone.processQueue();
                });

                myDropzone.on("success", function() {
                    window.dispatchEvent(new CustomEvent('close-modal', {
                        detail: {
                            name: 'import-siswa'
                        }
                    }));
                    window.location.reload();
                });

                myDropzone.on("error", function(file, errorMessage) {
                    alert("Gagal upload: " + (errorMessage.message ?? errorMessage));
                });
            });
        </script>
    @endpush
</x-modal>
