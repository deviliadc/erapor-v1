<x-sidebar.item
    title="Dashboard"
    icon="heroicon-o-home"
    href="{{ route('siswa.dashboard') }}"
    :page="$page"
    key="dashboard"
/>

<x-sidebar.item
    title="Mata Pelajaran"
    icon="heroicon-o-book-open"
    href="{{ route('siswa.nilai.index') }}"
    :page="$page"
    key="nilai"
/>
