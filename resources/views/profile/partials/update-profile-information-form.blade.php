<!-- Profile Info -->
<div class="p-5 mb-6 border border-gray-200 dark:border-gray-700 rounded-2xl lg:p-6">
    <div class="flex flex-col xl:flex-row items-start gap-6">
        <!-- Profile Picture + Edit -->
        <div class="relative w-20 h-20 shrink-0">
            <div
                class="w-20 h-20 flex items-center justify-center overflow-hidden border border-gray-300 dark:border-gray-700 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-400">
                @if ($user->profile_photo_path)
                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="user"
                        class="object-cover w-full h-full" />
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5.121 17.804A9.003 9.003 0 0112 15c2.485 0 4.735.996 6.364 2.636M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                @endif
            </div>
            <label for="profile_photo"
                class="absolute top-0 right-0 flex items-center justify-center w-6 h-6 bg-white border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 rounded-full shadow-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                <svg class="w-4 h-4 text-gray-600 dark:text-gray-200" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 20h9M16.5 3.5a2.121 2.121 0 113 3L6 20l-4 1 1-4L16.5 3.5z" />
                </svg>
                <input id="profile_photo" name="profile_photo" type="file" class="hidden" />
            </label>
        </div>


        <!-- Editable Profile Form -->
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
            class="w-full max-w-xl space-y-5">
            @csrf
            @method('PATCH')

            {{-- <!-- Username -->
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                <input type="text" value="{{ $user->username }}" disabled
                    class="w-full cursor-not-allowed rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 px-4 py-2.5 text-sm text-gray-600 dark:text-gray-400" />
            </div>

            <!-- Full Name -->
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
                <input type="text" value="{{ $user->name }}" disabled
                    class="w-full cursor-not-allowed rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 px-4 py-2.5 text-sm text-gray-600 dark:text-gray-400" />
            </div>

            <!-- Email -->
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                <x-form.input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Phone -->
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                <x-form.input-error :messages="$errors->get('phone')" class="mt-2" />
            </div> --}}

            <!-- Username (readonly) -->
            <x-form.input name="username" label="Username" :value="$user->username" readonly
                class="cursor-not-allowed bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400" />

            <!-- Full Name (readonly) -->
            <x-form.input name="name" label="Full Name" :value="$user->name" readonly
                class="cursor-not-allowed bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400" />

            <!-- Email -->
            <x-form.input name="email" label="Email" type="email" :value="old('email', $user->email)" />

            <!-- Phone -->
            <x-form.input name="phone" label="Phone" :value="old('phone', $user->phone)" />

            <!-- Save Button -->
            <div class="flex justify-end items-center gap-4">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 hover:bg-brand-600 shadow">
                    Save Changes
                </button>

                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-green-600 dark:text-green-400">
                        {{ __('Saved.') }}
                    </p>
                @endif
            </div>
        </form>
    </div>
</div>
