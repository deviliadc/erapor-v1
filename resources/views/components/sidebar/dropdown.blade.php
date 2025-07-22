@props([
    'title',
    'icon',
    'active' => false,
    'menuKey',
    'items' => [],
    'page' => '',
])

<li>
    <a href="#" @click.prevent="selected = (selected === '{{ $menuKey }}' ? '' : '{{ $menuKey }}')"
        class="menu-item group"
        :class="(selected === '{{ $menuKey }}' || {{ collect($items)->pluck('key')->contains($page) ? 'true' : 'false' }}) ? 'menu-item-active' : 'menu-item-inactive'">

        <x-dynamic-component :component="$icon"
            :class="(selected === '{{ $menuKey }}' || {{ collect($items)->pluck('key')->contains($page) ? 'true' : 'false' }}) ? 'menu-item-icon-active' : 'menu-item-icon-inactive'" />

        <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">{{ $title }}</span>

        <svg class="menu-item-arrow absolute right-2.5 top-1/2 -translate-y-1/2 stroke-current"
            :class="[(selected === '{{ $menuKey }}') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive', sidebarToggle ? 'lg:hidden' : '']"
            width="20" height="20" viewBox="0 0 20 20" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke=""
                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </a>

    <!-- Dropdown Menu Start -->
    <div class="overflow-hidden transform translate" :class="(selected === '{{ $menuKey }}') ? 'block' : 'hidden'">
        <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'" class="flex flex-col gap-1 mt-2 menu-dropdown pl-9">
            @foreach ($items as $item)
                <li>
                    <a href="{{ $item['href'] }}" class="menu-dropdown-item group"
                        :class="page === '{{ $item['key'] }}' ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive'">
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <!-- Dropdown Menu End -->
</li>
