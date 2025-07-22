@php
    $menus = [
        [
            'title' => 'Dashboard',
            'page' => 'Dashboard',
            'route' => 'admin.dashboard',
            'icon' => '<svg>...</svg>',
        ],
        [
            'title' => 'Forms',
            'icon' => '<svg>...</svg>',
            'children' => [
                ['title' => 'Form Elements', 'page' => 'formElements', 'route' => 'form.elements'],
            ],
        ],
    ];

    $page = 'Dashboard'; // ← definisikan di sini sesuai halaman aktif
    $selected = null;
    $sidebarToggle = false;
@endphp

<aside :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
    class="sidebar fixed left-0 top-0 z-50 flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 transition-all dark:border-gray-800 dark:bg-black lg:static lg:translate-x-0">

<x-sidebar-menu :menus="$menus" :page="$page" :selected="$selected" :sidebarToggle="$sidebarToggle" />
    </div>
</aside>
