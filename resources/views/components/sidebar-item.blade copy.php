@props([
    'items' => [],
    'sidebarToggle' => false,
])
<!-- Overlay untuk mobile -->
<div
    {{-- x-show="sidebarToggle"
    @click="sidebarToggle = false" --}}
x-show="$store.sidebar.toggle"
    @click="$store.sidebar.toggle = false"
    class="fixed inset-0 bg-black/50 z-[999998] lg:hidden">
</div>

{{-- <aside x-data="{ sidebarToggle: {{ $sidebarToggle ? 'true' : 'false' }}, isHovered: false }" @mouseenter="isHovered = true" @mouseleave="isHovered = false"
    @click.outside="sidebarToggle = false"
    :class="$store.sidebar.toggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
    class="sidebar fixed left-0 top-0 z-[999999] flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 duration-300 ease-linear dark:border-gray-800 dark:bg-black lg:static lg:translate-x-0"> --}}
<aside
    {{-- @click.outside="sidebarToggle = false" --}}
    {{-- :class="sidebarToggle ? 'translate-x-0' : '-translate-x-full'" --}}
    @click.outside="$store.sidebar.toggle = false"
        :class="$store.sidebar.toggle ? 'translate-x-0' : '-translate-x-full'"
    class="fixed left-0 top-0 z-[999999] flex h-screen w-[290px] flex-col
           overflow-y-hidden border-r border-gray-200 bg-white px-5
           duration-300 ease-linear dark:border-gray-800 dark:bg-black
           lg:static lg:translate-x-0">

 <!-- SIDEBAR HEADER -->
 <div :class="$store.sidebar.toggle ? 'justify-center' : 'justify-between'"
{{-- <div :class="sidebarToggle ? 'justify-center' : 'justify-between'" --}}
     class="sidebar-header flex items-center gap-2 pt-8 pb-7 px-4">

    <a href="{{ homeRouteForUser() }}" class="flex items-center justify-center">
        <!-- Logo besar (expand) -->
        {{-- <span x-show="!sidebarToggle" --}}
        <span x-show="$store.sidebar.toggle"
              x-transition
              class="logo flex items-center">
            <img class="dark:hidden h-9 object-cover rounded-full"
                 src="{{ asset('images/logo-1.png') }}" alt="Logo">
            <img class="hidden dark:block h-9 object-cover rounded-full"
                 src="{{ asset('images/logo-1-dark.png') }}" alt="Logo">
        </span>

        <!-- Logo kecil (collapse) -->
        {{-- <span x-show="sidebarToggle" --}}
        <span x-show="$store.sidebar.toggle"
              x-transition
              class="logo-icon flex items-center">
            <img class="dark:hidden w-10 h-10 object-cover rounded-full"
                 src="{{ asset('images/logo-app.png') }}" alt="Logo">
            <img class="hidden dark:block w-10 h-10 object-cover rounded-full"
                 src="{{ asset('images/logo-app-dark.png') }}" alt="Logo">
        </span>
    </a>
</div>


    <!-- SIDEBAR BODY -->
    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
        <nav>
            @foreach ($items as $group => $menuGroup)
                @if (count($menuGroup))
                    <div class="mb-6">
                        <h3 class="mb-4 text-xs uppercase text-gray-400">
                            {{-- <span :class="sidebarToggle && !isHovered ? 'hidden' : 'inline-block'"> --}}
                                <span :class="$store.sidebar.toggle && !$store.sidebar.isHovered ? 'hidden' : 'inline-block'">
                                    {{ $group }}
                                </span>
                        </h3>

                        <ul class="flex flex-col gap-4">
                            @foreach ($menuGroup as $item)
                                @php
                                    $hasChildren = isset($item['children']);
                                    // $isParentActive =
                                    //     request()->routeIs($item['route'] ?? '') ||
                                    //     ($hasChildren &&
                                    //         collect($item['children'])
                                    //             ->pluck('route')
                                    //             ->contains(fn($r) => request()->routeIs($r)));

                                    //  $isParentActive =
                                    //         (isset($item['route']) && $item['route'] && request()->routeIs($item['route']))
                                    //         ||
                                    //         ($hasChildren &&
                                    //             collect($item['children'])
                                    //                 ->pluck('route')
                                    //                 ->contains(fn($r) => request()->routeIs($r) || request()->routeIs('*.' . $r)));

                                    // $isParentActive =
                                    //     (($item['route'] ?? false) && request()->routeIs('*.' . $item['route'])) ||
                                    //     ($hasChildren &&
                                    //         collect($item['children'])
                                    //             ->pluck('route')
                                    //             ->contains(fn($r) => request()->routeIs('*.' . $r)));

                                    // $isParentActive =
                                    //     (isset($item['route']) && request()->routeIs($item['route']))
                                    //     || ($hasChildren && collect($item['children'])->pluck('route')->contains(fn($r) => request()->routeIs($r)));

                                    $isParentActive =
                                        (isset($item['route']) &&
                                            $item['route'] &&
                                            (request()->routeIs($item['route']) ||
                                                request()->routeIs('*.' . $item['route']))) ||
                                        ($hasChildren &&
                                            collect($item['children'])
                                                ->pluck('route')
                                                ->contains(
                                                    fn($r) => request()->routeIs($r) || request()->routeIs('*.' . $r),
                                                ));
                                @endphp

                                <li x-data="{ open: {{ $isParentActive ? 'true' : 'false' }} }">
                                    <a href="{{ $hasChildren ? '#' : role_route($item['route']) }}"
                                        @if ($hasChildren) @click.prevent="open = !open" @endif
                                        class="menu-item group flex items-center justify-between {{ $isParentActive ? 'menu-item-active' : 'menu-item-inactive' }}">
                                        <div class="flex items-center gap-3">
                                            <div class="w-6 h-6 shrink-0 flex items-center justify-center">
                                                {!! $item['icon'] ?? '' !!}
                                            </div>

                                            <span class="menu-item-text"
                                                :class="sidebarToggle && !isHovered ? 'hidden' : ''">
                                                {{ $item['label'] }}
                                            </span>
                                        </div>

                                        @if ($hasChildren)
                                            <template x-if="!sidebarToggle || isHovered">
                                                <svg class="menu-item-arrow absolute right-4 top-1/2 -translate-y-1/2 stroke-current transition-transform duration-200"
                                                    :class="{ 'rotate-180': open }" width="20" height="20"
                                                    viewBox="0 0 20 20" fill="none">
                                                    <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585"
                                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </template>
                                        @endif
                                    </a>

                                    @if ($hasChildren)
                                        <div x-show="open && (!sidebarToggle || isHovered)" x-collapse x-cloak
                                            class="pl-9 mt-2">
                                            <ul class="flex flex-col gap-1">
                                                @foreach ($item['children'] as $child)
                                                    <li>
                                                        {{-- <a href="{{ route($child['route']) }}" --}}
                                                        <a href="{{ role_route($child['route']) }}"
                                                            {{-- class="menu-dropdown-item group {{ request()->routeIs($child['route']) ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive' }}"> --}}
                                                            class="menu-dropdown-item group {{ request()->routeIs('*.' . $child['route']) ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive' }}">
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
                @endif
            @endforeach
        </nav>
    </div>
</aside>
