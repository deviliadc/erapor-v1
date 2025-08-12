@props([
    'name',             // string, unique modal ID
    'title',            // string, title modal (e.g., "Import Siswa")
    'templateUrl',      // string, URL untuk download template
    'action',           // string, route/URL ke controller import
    'accept' => '.xlsx,.xls', // optional: file types
])

<x-modal :name="$name" :title="$title">
    <form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-6 p-6">
        @csrf

        <!-- Template download -->
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-600 dark:text-gray-300">Unduh file template terlebih dahulu.</p>
            <a href="{{ $templateUrl }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow">
                Download Template
            </a>
        </div>

        <!-- File upload -->
        <div>
            <label for="file" class="block text-sm font-medium text-gray-700 dark:text-white mb-2">Upload File Excel</label>
            <input type="file" name="file" id="file" accept="{{ $accept }}"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-white" />
        </div>

        <!-- Submit -->
        <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow">
                Import
            </button>
        </div>
    </form>
</x-modal>
