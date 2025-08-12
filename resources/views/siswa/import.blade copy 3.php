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
                <button type="button" id="browse-btn"
                    class="text-theme-sm text-brand-500 font-medium underline cursor-pointer">
                    Browse File
                </button>
            </div>

            {{-- Container Preview --}}
            <div id="dz-previews" class="mt-4"></div>
        </form>

        {{-- Tombol Submit --}}
        <div class="mt-6 flex justify-end">
            <button type="button" id="upload-btn"
                class="px-4 py-2 bg-brand-500 text-white rounded-lg hover:bg-brand-600 focus:ring-2 focus:ring-brand-400">
                Upload & Import
            </button>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inisialisasi Dropzone
            Dropzone.autoDiscover = false;

var myDropzone = new Dropzone("#import-dropzone", {
    autoProcessQueue: false,
    previewsContainer: "#dz-previews",
    clickable: false,
    maxFiles: 1,
    acceptedFiles: ".xls,.xlsx",
    dictDefaultMessage: "",
});

// Supaya kalau upload file baru, file lama dihapus supaya maxFiles terpenuhi
myDropzone.on("addedfile", function() {
    if (myDropzone.files.length > 1) {
        myDropzone.removeFile(myDropzone.files[0]);
    }
});

document.getElementById('browse-btn').addEventListener('click', function () {
    myDropzone.hiddenFileInput.click();
});

// document.getElementById('upload-btn').addEventListener('click', function () {
//     if (myDropzone.getQueuedFiles().length === 0) {
//         alert('Silakan pilih file terlebih dahulu!');
//         return;
//     }
//     myDropzone.processQueue();
// });
document.getElementById('upload-btn').addEventListener('click', function () {
    if (myDropzone.getAcceptedFiles().length === 0) {
        alert('Silakan pilih file Excel terlebih dahulu!');
        return;
    }
    myDropzone.processQueue();
});

myDropzone.on("success", function(file, response) {
    alert('Import berhasil!');
    // Bisa tambah aksi reload atau tutup modal
});

myDropzone.on("error", function(file, errorMessage) {
    alert('Gagal import: ' + errorMessage);
});
        });
    </script>
    @endpush
</x-modal>
