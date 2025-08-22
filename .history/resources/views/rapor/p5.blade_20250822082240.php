{{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\rapor\p5.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rapor P5 - {{ $siswa->nama }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; }
        .header { text-align: center; font-weight: bold; font-size: 16px; margin-bottom: 12px; }
        .subheader { text-align: left; font-size: 14px; margin-bottom: 18px; }
        .info-table td { padding: 2px 8px; vertical-align: top; }
        .main-table, .main-table th, .main-table td { border: 1px solid #333; border-collapse: collapse; }
        .main-table th, .main-table td { padding: 6px 8px; }
        .main-table th { background: #f3f3f3; }
        .dimensi { font-weight: bold; background: #f9f9f9; }
        .catatan { margin-top: 18px; }
        .footer-table td { padding: 8px 12px; }
        .keterangan-table, .keterangan-table th, .keterangan-table td { border: 1px solid #333; border-collapse: collapse; }
        .keterangan-table th, .keterangan-table td { padding: 6px 8px; text-align: center; }
        .ttd { margin-top: 32px; }
        .ttd td { vertical-align: top; }
        .ttd .nama { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">RAPOR<br>PROYEK PENGUATAN PROFIL PELAJAR PANCASILA</div>

    <table class="info-table" width="100%">
        <tr>
            <td>Nama Peserta Didik</td><td>: {{ $siswa->nama }}</td>
            <td>Kelas</td><td>: {{ $kelas->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td>NISN</td><td>: {{ $siswa->nisn }}</td>
            <td>No. Induk</td><td>: {{ $siswa->nipd }}</td>
        </tr>
        <tr>
            <td>Sekolah</td><td>: {{ $sekolah->nama ?? '-' }}</td>
            <td>Fase</td><td>: {{ $fase }}</td>
        </tr>
        <tr>
            <td>Alamat</td><td>: {{ $sekolah->alamat ?? '-' }}</td>
            <td>Semester</td><td>: {{ $semester }}</td>
        </tr>
        <tr>
            <td></td><td></td>
            <td>Tahun Ajaran</td><td>: {{ $tahunAjaran }}</td>
        </tr>
    </table>

    @forelse($p5Data as $p5)
        <div class="subheader" style="margin-top:18px;">
            PROYEK : {{ $p5['proyek']->nama_proyek ?? '-' }}
        </div>
        <div style="margin-bottom:12px;">
            {{ $p5['proyek']->deskripsi ?? '-' }}
        </div>

        <table class="main-table" width="100%">
            <tr>
                <th>Dimensi</th>
                <th>Sub Elemen</th>
                <th>Predikat</th>
                <th>Deskripsi</th>
            </tr>
            @foreach($p5['capaian'] as $c)
                <tr>
                    <td>{{ $c['dimensi'] }}</td>
                    <td>{{ $c['sub_elemen'] }}</td>
                    <td>{{ $c['predikat'] }}</td>
                    <td>{{ $c['deskripsi'] }}</td>
                </tr>
            @endforeach
        </table>

        <div class="catatan">
            <strong>Catatan Proses</strong><br>
            {{ $p5['catatan'] ?? '-' }}
        </div>
        <hr>
    @empty
        <div class="text-center text-gray-500 py-8">Data P5 tidak ditemukan untuk semester ini.</div>
    @endforelse

    <div style="margin-top:28px; font-weight:bold;">KETERANGAN TINGKAT PENCAPAIAN SISWA</div>
    <table class="keterangan-table" width="100%">
        <tr>
            <th>BB</th>
            <th>MB</th>
            <th>BSH</th>
            <th>SB</th>
        </tr>
        <tr>
            <td>Belum Berkembang</td>
            <td>Mulai Berkembang</td>
            <td>Berkembang sesuai harapan</td>
            <td>Sangat Berkembang</td>
        </tr>
        <tr>
            <td>Siswa masih membutuhkan bimbingan dalam mengembangkan kemampuan</td>
            <td>Siswa mulai mengembangkan kemampuan namun masih belum ajek</td>
            <td>Siswa telah mengembangkan kemampuan hingga berada dalam tahap ajek</td>
            <td>Siswa mengembangkan kemampuannya melampaui harapan</td>
        </tr>
    </table>

    <table class="ttd" width="100%" style="margin-top:32px;">
    <tr>
        <td width="50%"style="text-align:center;">
            Mengetahui,<br>Orang Tua
            <br><br><br><br>
            ............
        </td>
        <td width="50%" style="text-align:center;">
            {{ $sekolah->kecamatan ?? '-' }}, {{ $tanggal ?? date('d F Y') }}<br>
            Wali Kelas<br><br><br><br>
            <span class="nama">{{ $waliKelas->nama ?? '-' }}</span><br>
            NIP: {{ $waliKelas->nip ?? '-' }}
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align:center; padding-top:40px;">
            Mengetahui<br>Kepala Sekolah<br><br><br><br>
            <span class="nama">{{ $pengaturan->nama_kepala_sekolah ?? 'Kepala Sekolah' }}</span><br>
            NIP: {{ $pengaturan->nip_kepala_sekolah ?? '-' }}
        </td>
    </tr>
</table>

</body>
</html>
