@php
    $role = auth()->user()->role;
@endphp

@if ($role === 'admin')
    @include('components.sidebar.admin')
@elseif ($role === 'guru')
    @include('components.sidebar.guru')
@elseif ($role === 'siswa')
    @include('components.sidebar.siswa')
@elseif ($role === 'kepsek')
    @include('components.sidebar.kepsek')
@elseif ($role === 'wali kelas')
    @include('components.sidebar.wali-kelas')
@endif
