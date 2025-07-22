{{-- <x-app-layout>
    <div class="p-4 mx-auto max-w-screen-2xl md:p-6">
        <!-- Breadcrumb -->
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

        <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 lg:p-6">
            <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white lg:mb-7">
                Profile
            </h3>

            <!-- Profile Card -->
            <div class="p-5 mb-6 border border-gray-200 dark:border-gray-700 rounded-2xl lg:p-6">
                <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                    <div class="flex flex-col items-center w-full gap-6 xl:flex-row relative">
                        <!-- Profile Picture -->
                        <div class="relative w-20 h-20">
                            <div class="w-20 h-20 overflow-hidden border border-gray-200 dark:border-gray-600 rounded-full">
                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="user" class="object-cover w-full h-full" />
                            </div>
                            <button
                                @click="isEditPhotoModal = true"
                                class="absolute bottom-0 right-0 flex items-center justify-center w-6 h-6 rounded-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 shadow hover:bg-gray-100 dark:hover:bg-gray-700"
                                title="Edit photo"
                            >
                                <svg class="w-3.5 h-3.5 text-gray-600 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M17.414 2.586a2 2 0 010 2.828l-1.414 1.414-2.828-2.828 1.414-1.414a2 2 0 012.828 0zM3 17.25V21h3.75l9.29-9.29-3.75-3.75L3 17.25z" />
                                </svg>
                            </button>
                        </div>

                        <!-- Profile Info -->
                        <div class="order-3 xl:order-2">
                            <h4 class="mb-2 text-lg font-semibold text-center text-gray-800 dark:text-white xl:text-left">
                                {{ $user->name }}
                            </h4>
                            <div class="flex flex-col items-center gap-1 text-center xl:flex-row xl:gap-3 xl:text-left">
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->student_id }}</p>
                                <div class="hidden h-3.5 w-px bg-gray-300 dark:bg-gray-600 xl:block"></div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->batch }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="p-5 mb-6 border border-gray-200 dark:border-gray-700 rounded-2xl lg:p-6">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-white lg:mb-6">
                            Personal Information
                        </h4>
                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                            <div class="col-span-full">
                                <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">Full Name</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $user->name }}</p>
                            </div>
                            <div>
                                <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">Email address</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $user->email }}</p>
                            </div>
                            <div>
                                <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">Phone</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $user->phone }}</p>
                            </div>
                        </div>
                    </div>
                    <button
                        @click="isProfileInfoModal = true"
                        class="flex w-full items-center justify-center gap-2 rounded-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 shadow hover:bg-gray-50 dark:hover:bg-gray-700 lg:w-auto"
                    >
                        Edit
                    </button>
                </div>
            </div>

            <!-- Address Section -->
            <div class="p-5 border border-gray-200 dark:border-gray-700 rounded-2xl lg:p-6">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-white lg:mb-6">
                            Address
                        </h4>
                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                            <div>
                                <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">Country</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $user->country }}</p>
                            </div>
                            <div>
                                <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">City/State</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $user->city }}</p>
                            </div>
                            <div>
                                <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">Postal Code</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $user->postal_code }}</p>
                            </div>
                            <div>
                                <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">TAX ID</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $user->tax_id }}</p>
                            </div>
                        </div>
                    </div>
                    <button
                        @click="isProfileAddressModal = true"
                        class="flex w-full items-center justify-center gap-2 rounded-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 shadow hover:bg-gray-50 dark:hover:bg-gray-700 lg:w-auto"
                    >
                        Edit
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}
