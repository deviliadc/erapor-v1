@component('mail::message')
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('images/logo-app.png') }}" alt="Logo Sekolah" style="height: 60px;">
</div>

# Halo, {{ $user->name }}!

Kami menerima permintaan untuk mengatur ulang password akun Anda.

@component('mail::button', ['url' => $url, 'color' => 'primary'])
Atur Ulang Password
@endcomponent

Jika Anda tidak meminta pengaturan ulang password, Anda bisa mengabaikan email ini.

Terima kasih,<br>
**{{ config('app.name') }}**
@endcomponent
