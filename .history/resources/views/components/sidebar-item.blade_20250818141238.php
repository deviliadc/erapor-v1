@props([
    'items' => [],
    'sidebarToggle' => false,
])

<aside x-data="{ sidebarToggle: {{ $sidebarToggle ? 'true' : 'false' }}, isHovered: false }" @mouseenter="isHovered = true" @mouseleave="isHovered = false"
    @click.outside="sidebarToggle = false" {{-- :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'" --}}
    :class="$store.sidebar.toggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
    class="sidebar fixed left-0 top-0 z-[999999] flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 duration-300 ease-linear dark:border-gray-800 dark:bg-black lg:static lg:translate-x-0">

    <!-- SIDEBAR HEADER -->
    {{-- <div class="pt-8 pb-7 px-4">
        <a href="{{ homeRouteForUser() }}" class="flex items-center">
            <img src="{{ asset('images/logo-1.png') }}" alt="Logo SDN Darmorejo 02"
                class="h-9 w-10 rounded-full object-cover transition-all duration-300" />
            <span class="ml-3 text-lg font-semibold text-gray-800 dark:text-white transition-all duration-300"
                :class="sidebarToggle ? 'hidden' : 'inline-block'">
                SDN Darmorejo 02
            </span>
        </a>
    </div> --}}
       {{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\components\sidebar-item.blade.php --}}

    <div class="pt-8 pb-7 px-4">
        <a href="{{ homeRouteForUser() }}" class="flex items-center justify-center">
            <img
                x-show="!sidebarToggle || isHovered"
                src="{{ asset('images/logo-1.png') }}"
                alt="Logo SDN Darmorejo 02"
                class="transition-all duration-300 w-full h-9 object-cover rounded-full"
            />
            <img
                x-show="sidebarToggle && !isHovered"
                src="{{ asset('images/logo-app.png') }}"
                alt="Logo SDN Darmorejo 02"
                class="transition-all duration-300 w-10 h-9 object-cover rounded-full"
            />
        </a>
    </div>
    <!-- SIDEBAR BODY -->
    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
        <nav>
            @foreach ($items as $group => $menuGroup)
                @if (count($menuGroup))
                    <div class="mb-6">
                        <h3 class="mb-4 text-xs uppercase text-gray-400">
                            <span :class="sidebarToggle && !isHovered ? 'hidden' : 'inline-block'">
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
