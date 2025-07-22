@foreach ($users as $user)
    <x-modal name="edit-modal-{{ $user['id'] }}" title="Edit User" maxWidth="2xl">
        <form action="{{ route('user.update', $user['id']) }}" method="POST" class="space-y-6 sm:p-6">
            @csrf
            @method('PUT')

            {{-- Username --}}
            <x-form.input label="Username" name="username" :value="old('username', $user['username'])" required />

            {{-- Email --}}
            <x-form.input label="Email" name="email" :value="old('email', $user['email'])" type="email" required />

            {{-- Password Fields --}}
            @php
                $passwordFields = [
                    ['name' => 'password', 'label' => 'Password Baru'],
                    ['name' => 'password_confirmation', 'label' => 'Konfirmasi Password'],
                ];
            @endphp

            @foreach ($passwordFields as $field)
                <x-form.password :name="$field['name']" :label="$field['label']" />
            @endforeach

            {{-- Roles --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Roles <span class="text-error-500">*</span>
                </label>
                <div class="flex flex-wrap gap-4">
                    @foreach ($roles as $role)
                        <label
                            class="flex items-center text-sm font-medium text-gray-700 cursor-pointer select-none dark:text-gray-400 space-x-2"
                            x-data="{ selected: {{ in_array($role->id, $user['role_ids']) ? 'true' : 'false' }} }">
                            <div class="relative">
                                <input type="checkbox" id="role_edit_{{ $role->id }}" name="roles[]"
                                    value="{{ $role->id }}" class="sr-only" @change="selected = !selected"
                                    :checked="selected">

                                <div :class="selected ? 'border-brand-500 bg-brand-500' :
                                    'bg-transparent border-gray-300 dark:border-gray-700'"
                                    class="flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] transition-colors duration-200">
                                    <span :class="selected ? 'opacity-100' : 'opacity-0'"
                                        class="transition-opacity duration-150">
                                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white"
                                                stroke-width="1.94437" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="p-1"><span class="select-none">{{ $role->label }}</span></div>
                        </label>
                    @endforeach
                </div>

                @error('roles')
                    <div class="mt-1 text-sm text-red-600 dark:text-red-400">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>
@endforeach
