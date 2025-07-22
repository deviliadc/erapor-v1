{{-- Root div harus punya x-data --}}
<div x-data="{ selected: '{{ $selected ?? '' }}' }">

    {{-- Dashboard (item biasa) --}}
    <x-sidebar.item
        href="{{ route('admin.dashboard') }}"
        icon="heroicon-o-home"
        text="Dashboard"
        :active="$page === 'dashboard'"
    />

    {{-- Dropdown: Kelola User --}}
    <x-sidebar.dropdown
        title="Kelola User"
        menuKey="KelolaUser"
        icon="heroicon-o-user-group"
        :page="$page ?? ''"
        :items="[
            ['key' => 'siswa', 'label' => 'Siswa', 'href' => route('admin.siswa.index')],
            ['key' => 'guru', 'label' => 'Guru', 'href' => route('admin.guru.index')],
            ['key' => 'user', 'label' => 'User', 'href' => route('admin.user.index')],
        ]"
    />

    {{-- Dropdown: Kelola Kelas --}}
    <x-sidebar.dropdown
        title="Kelola Kelas"
        menuKey="KelolaKelas"
        icon="heroicon-o-academic-cap"
        :page="$page ?? ''"
        :items="[
            ['key' => 'kelas', 'label' => 'Daftar Kelas', 'href' => route('admin.kelas.index')],
            ['key' => 'mapel', 'label' => 'Mata Pelajaran', 'href' => route('admin.mapel.index')],
        ]"
    />

    {{-- Tambah dropdown lain sesuai kebutuhan --}}
</div>
