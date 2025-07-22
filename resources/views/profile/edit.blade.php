<x-app-layout>
        <!-- Breadcrumb -->
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

        {{-- <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 lg:p-6">
            <!-- Profile Section -->
            <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white lg:mb-7">
                Profile
            </h3>
            @include('profile.partials.update-profile-information-form')

            <!-- Password Section -->
            <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white lg:mb-7">
                Password
            </h3>
            @include('profile.partials.update-password-form')
        </div> --}}
        {{-- <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 lg:p-6"> --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Profile Section -->
        <div>
            {{-- <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white lg:mb-7">
                Profile
            </h3> --}}
            @include('profile.partials.update-profile-information-form')
        </div>

        <!-- Password Section -->
        <div>
            {{-- <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white lg:mb-7">
                Password
            </h3> --}}
            @include('profile.partials.update-password-form')
        </div>
    </div>
{{-- </div> --}}

</x-app-layout>
