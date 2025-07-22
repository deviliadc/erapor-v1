{{-- resources/views/dashboard.blade.php --}}
@if ($user->role === 'admin')
    @include('dashboard.admin')
@elseif ($user->role === 'guru')
    @include('dashboard.guru')
@elseif ($user->role === 'siswa')
    @include('dashboard.siswa')
@elseif ($user->role === 'wali_kelas')
    @include('dashboard.wali_kelas')
@elseif ($user->role === 'kepala_sekolah')
    @include('dashboard.kepsek')
@endif
