@foreach ($data as $item)
{{-- <x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"> --}}
            <x-modal name="edit-modal-{{ $item['id'] }}" maxWidth="md">
@include('bab.edit', ['bab' => $item])
            <div class="px-5 py-4 sm:px-6 sm:py-5">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Edit Bab</h3>
            </div>

            <form action="{{ role_route('bab.update', $item['id']) }}" method="POST" enctype="multipart/form-data"
                class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <x-form.input label="Nama Bab" name="nama" :value="old('nama', $item['nama'])" />

                {{-- Submit --}}
                <div>
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                        Update
                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M9.25 5C9.25 4.59 9.59 4.25 10 4.25C10.41 4.25 10.75 4.59 10.75 5V9.25H15C15.41 9.25 15.75 9.59 15.75 10C15.75 10.41 15.41 10.75 15 10.75H10.75V15C10.75 15.41 10.41 15.75 10 15.75C9.59 15.75 9.25 15.41 9.25 15V10.75H5C4.59 10.75 4.25 10.41 4.25 10C4.25 9.59 4.59 9.25 5 9.25H9.25V5Z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        </x-modal>
    {{-- </div>
</x-app-layout> --}}
@endforeach
