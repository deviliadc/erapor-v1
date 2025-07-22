<x-app-layout>
    <div class="p-4 mx-auto max-w-screen-2xl md:p-6">
        <!-- Breadcrumb -->
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

        <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 lg:p-6">
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
        </div>
    </div>
</x-app-layout>
