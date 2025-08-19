<x-modal name="import-presensi-harian" title="Import Data Presensi Harian">
    <div class="p-4 space-y-4">

        {{-- Download Template --}}
        <div class="flex items-center gap-5 mb-4">
            <a href="{{ role_route('presensi-harian.template') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs ring-1 ring-inset ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                </svg>
                Download Template Excel
            </a>
        </div>

        {{-- Form Upload --}}
        <form id="import-dropzone" action="{{ role_route('guru.import') }}" method="POST"
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
                <button type="button" id="browse-btn"
                    class="text-theme-sm text-brand-500 font-medium underline cursor-pointer">
                    Browse File
                </button>
            </div>

            <input type="file" id="real-file" name="file" style="display:none" accept=".xlsx,.xls,.csv" />

            <div id="dz-previews" class="mt-4"></div>
        </form>

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
        const uploadBtn = document.getElementById("upload-btn");
        const dzPreviews = document.getElementById("dz-previews");

        if (!uploadBtn || !dzPreviews) return; // Hindari error jika elemen tidak ditemukan

        const myDropzone = new Dropzone("#import-dropzone", {
            autoProcessQueue: false,
            clickable: "#browse-btn",
            paramName: "file",
            maxFiles: 1,
            acceptedFiles: ".xlsx,.xls,.csv",
            previewsContainer: "#dz-previews",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            previewTemplate: `
                <div class="dz-preview dz-file-preview flex items-center gap-4 p-3 border rounded-lg bg-white dark:bg-gray-800">
                    <div class="flex-shrink-0">
                        <svg class="w-10 h-10 text-success-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 2h14l4 4v16a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200" data-dz-name></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400" data-dz-size></p>
                        <div class="w-full bg-gray-200 rounded h-2 mt-2">
                            <div class="bg-success-500 h-2 rounded" data-dz-uploadprogress></div>
                        </div>
                        <strong class="text-error-500 text-xs" data-dz-errormessage></strong>
                    </div>
                    <button data-dz-remove class="text-error-500 hover:text-error-700 text-sm font-medium">Hapus</button>
                </div>
            `
        });

        myDropzone.on("addedfile", function(file) {
            document.querySelector(".dz-message").style.display = "none";
        });

        myDropzone.on("removedfile", function(file) {
            if (myDropzone.getAcceptedFiles().length === 0) {
                document.querySelector(".dz-message").style.display = "";
            }
        });

        myDropzone.on("uploadprogress", function(file, progress) {
            const progressBar = dzPreviews.querySelector("[data-dz-uploadprogress]");
            if (progressBar) {
                progressBar.style.width = progress + "%";
            }
            uploadBtn.disabled = true;
            uploadBtn.innerText = "Uploading...";
        });

        uploadBtn.addEventListener("click", function() {
            if (myDropzone.getAcceptedFiles().length === 0) {
                alert("Pilih file Excel terlebih dahulu.");
                return;
            }
            myDropzone.processQueue();
        });

        document.getElementById("import-dropzone").addEventListener("submit", function(e) {
            e.preventDefault();
        });

        // myDropzone.on("success", function(file, response) {
        //     uploadBtn.disabled = false;
        //     uploadBtn.innerText = "Upload & Import";
        //     alert(response.message || "Import berhasil.");
        //     window.dispatchEvent(new CustomEvent('close-modal', {
        //         detail: { name: 'import-guru' }
        //     }));
        //     window.location.reload();
        // });
        myDropzone.on("success", function(file, response) {
    uploadBtn.disabled = false;
    uploadBtn.innerText = "Upload & Import";
    alert(response.message || "Import berhasil.");
    window.dispatchEvent(new CustomEvent('close-modal', {
        detail: { name: 'import-guru' }
    }));
    window.location.reload();
    // Kosongkan error message di preview
    const errElem = file.previewElement.querySelector("[data-dz-errormessage]");
    if (errElem) {
        errElem.textContent = '';
    }
});

        // myDropzone.on("error", function(file, errorMessage, xhr) {
        //     uploadBtn.disabled = false;
        //     uploadBtn.innerText = "Upload & Import";
        //     let msg = "Gagal upload: ";
        //     if (xhr && xhr.response) {
        //         try {
        //             const res = JSON.parse(xhr.response);
        //             msg += res.message ? res.message : errorMessage;
        //         } catch (e) {
        //             msg += errorMessage;
        //         }
        //     } else {
        //         msg += errorMessage;
        //     }
        //     alert(msg);
        // });
        myDropzone.on("error", function(file, errorMessage, xhr) {
    uploadBtn.disabled = false;
    uploadBtn.innerText = "Upload & Import";
    let msg = "Gagal upload: ";
    if (xhr && xhr.response) {
        try {
            const res = JSON.parse(xhr.response);
            msg += res.message ? res.message : errorMessage;
        } catch (e) {
            msg += typeof errorMessage === 'string' ? errorMessage : (errorMessage.message || 'Terjadi kesalahan.');
        }
    } else {
        msg += typeof errorMessage === 'string' ? errorMessage : (errorMessage.message || 'Terjadi kesalahan.');
    }
    alert(msg);
    // Perbaiki error message di preview
    const errElem = file.previewElement.querySelector("[data-dz-errormessage]");
    if (errElem) {
        errElem.textContent = msg;
    }
});

        myDropzone.on("complete", function(file) {
            uploadBtn.disabled = false;
            uploadBtn.innerText = "Upload & Import";
        });
    });
    </script>
    @endpush
</x-modal>
