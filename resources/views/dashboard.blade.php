{{-- resources/views/dashboard.blade.php --}}
@if ($user->role === 'admin')
    @include('dashboard.admin')
@elseif ($user->role === 'guru')
    @include('dashboard.guru')
@elseif ($user->role === 'siswa')
    @include('dashboard.siswa')
