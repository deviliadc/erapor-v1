@props([
    'items' => [], // array menu: [label, icon, route, children => [submenu]]
    'sidebarToggle' => false,
    'selected' => '',
])

@php
    $isActive = fn($item) => request()->routeIs($item['route'] ?? '') ||
        (isset($item['children']) &&
            collect($item['children'])->pluck('route')->contains(fn($r) => request()->routeIs($r)));
@endphp

<aside
    {{-- :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
    class="sidebar fixed left-0 top-0 z-50 flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 transition-all dark:border-gray-800 dark:bg-black lg:static lg:translate-x-0"> --}}
    :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
    class="sidebar fixed left-0 top-0 z-[500] flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 duration-300 ease-linear dark:border-gray-800 dark:bg-black lg:static lg:translate-x-0"
    @click.outside="sidebarToggle = false"

    <!-- SIDEBAR HEADER -->
    <div class="pt-8 pb-7 px-4">
        <a href="{{ homeRouteForUser() }}" class="flex items-center">
            <img src="{{ asset('images/logo-app.png') }}" alt="Logo SDN Darmorejo 02"
                class="h-9 w-10 rounded-full object-cover transition-all duration-300" />
            <span class="ml-3 text-lg font-semibold text-gray-800 dark:text-white transition-all duration-300"
                :class="sidebarToggle ? 'hidden' : 'inline-block'">
                SDN Darmorejo 02
            </span>
        </a>
    </div>
    <!-- END SIDEBAR HEADER -->

    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
        <nav x-data="{ selected: $persist('$selected') }">
            @foreach ($items as $group => $menuGroup)
                <div class="mb-6">
                    <h3 class="mb-4 text-xs uppercase text-gray-400">
                        <span :class="sidebarToggle && !isHovered ? 'hidden' : 'inline-block'">{{ $group }}</span>
                    </h3>

                    <ul class="flex flex-col gap-4">
    @foreach ($menuGroup as $item)
        @php
            $hasChildren = isset($item['children']);
            $isActive = $isActive($item);
        @endphp

        <li x-data="{ open: {{ $isActive ? 'true' : 'false' }} }">
            <a
                href="{{ $hasChildren ? '#' : route($item['route']) }}"
                @click.prevent="{{ $hasChildren ? 'open = !open' : '' }}"
                class="menu-item group flex items-center justify-between"
                :class="open ? 'menu-item-active' : 'menu-item-inactive'"
            >
                <div class="flex items-center gap-3">
                    {!! $item['icon'] ?? '' !!}
                    <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                        {{ $item['label'] }}
                    </span>
                </div>

                @if ($hasChildren)
                    <svg
                        class="menu-item-arrow absolute right-4 top-1/2 -translate-y-1/2 stroke-current transition-transform duration-200"
                        :class="{ 'rotate-180': open }"
                        width="20" height="20"
                        viewBox="0 0 20 20"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585"
                            stroke="currentColor"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                @endif
            </a>

            @if ($hasChildren)
                <div x-show="open" class="pl-9 mt-2" x-transition>
                    <ul class="flex flex-col gap-1">
                        @foreach ($item['children'] as $child)
                            <li>
                                <a
                                    href="{{ route($child['route']) }}"
                                    class="menu-dropdown-item group
                                        {{ request()->routeIs($child['route']) ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive' }}"
                                >
                                    {{ $child['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </li>
    @endforeach
</ul>
                </div>
            @endforeach
        </nav>
    </div>
</aside>
