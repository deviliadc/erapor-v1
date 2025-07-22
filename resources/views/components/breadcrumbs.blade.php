@props(['title' => 'Dashboard', 'breadcrumbs' => []])

<div x-data="{ pageName: `{{ $title }}` }" class="mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3">
        <!-- Page Title -->
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90" x-text="pageName">
            {{ $title }}
        </h2>

        <!-- Breadcrumbs -->
        <nav class="w-full sm:w-auto">
            <ol class="flex flex-wrap items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400">
                <!-- Home -->
                <li class="flex items-center">
                    <a class="inline-flex items-center gap-1.5 hover:underline" href="{{ homeRouteForUser() }}">
                        Home
                    </a>
                </li>

                @forelse ($breadcrumbs as $crumb)
                    <!-- Separator -->
                    <li class="mx-1 text-gray-400">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5l7 7-7 7" />
                        </svg>
                    </li>

                    <!-- Crumb -->
                    <li class="flex items-center">
                        @if (isset($crumb['url']))
                            <a href="{{ $crumb['url'] }}" class="hover:underline text-gray-800 dark:text-white/90">
                                {{ $crumb['label'] }}
                            </a>
                        @else
                            <span class="text-gray-800 dark:text-white/90">{{ $crumb['label'] }}</span>
                        @endif
                    </li>
                @empty
                    <!-- Fallback to title if no breadcrumbs provided -->
                    <li class="mx-1 text-gray-400">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5l7 7-7 7" />
                        </svg>
                    </li>
                    <li class="flex items-center">
                        <span class="text-gray-800 dark:text-white/90">{{ $title }}</span>
                    </li>
                @endforelse
            </ol>
        </nav>
    </div>
</div>
