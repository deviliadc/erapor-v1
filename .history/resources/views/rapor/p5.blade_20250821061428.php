{{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\rapor\p5.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rapor P5 - {{ $siswa->nama }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; }
        .header { text-align: center; font-weight: bold; font-size: 16px; margin-bottom: 12px; }
        .subheader { text-align: center; font-size: 14px; margin-bottom: 18px; }
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
            <td>Kelas</td><td>: {{ $kelas->nama }}</td>
        </tr>
        <tr>
            <td>NISN</td><td>: {{ $siswa->nisn }}</td>
            <td>No. Induk</td><td>: {{ $siswa->nipd }}</td>
        </tr>
        <tr>
            <td>Sekolah</td><td>: {{ $sekolah->nama }}</td>
            <td>Fase</td><td>: {{ $fase }}</td>
        </tr>
        <tr>
            <td>Alamat</td><td>: {{ $sekolah->alamat }}</td>
            <td>Semester</td><td>: {{ $semester }}</td>
        </tr>
        <tr>
            <td></td><td></td>
            <td>Tahun Ajaran</td><td>: {{ $tahunAjaran }}</td>
        </tr>
    </table>

    <div class="subheader" style="margin-top:18px;">
        PROYEK {{ $proyek->nomor }} : {{ $proyek->judul }}
    </div>

    <div style="margin-bottom:12px;">
        {{ $proyek->deskripsi }}
    </div>

    <table class="main-table" width="100%">
        <tr>
            <th rowspan="2" width="32%">Dimensi/Elemen/Sub Elemen</th>
            <th colspan="4">Capaian</th>
        </tr>
        <tr>
            <th width="7%">BB</th>
            <th width="7%">MB</th>
            <th width="7%">BSH</th>
            <th width="7%">SB</th>
        </tr>
        @foreach($proyek->capaian as $c)
            <tr>
                <td class="dimensi">{{ $c['dimensi'] }}</td>
                <td colspan="4"></td>
            </tr>
            @foreach($c['elemen'] as $elemen)
                <tr>
                    <td>{{ $elemen['nama'] }}</td>
                    <td style="text-align:center;">{!! $elemen['nilai'] == 'BB' ? '&#10003;' : '' !!}</td>
                    <td style="text-align:center;">{!! $elemen['nilai'] == 'MB' ? '&#10003;' : '' !!}</td>
                    <td style="text-align:center;">{!! $elemen['nilai'] == 'BSH' ? '&#10003;' : '' !!}</td>
                    <td style="text-align:center;">{!! $elemen['nilai'] == 'SB' ? '&#10003;' : '' !!}</td>
                </tr>
            @endforeach
        @endforeach
    </table>

    <div class="catatan">
        <strong>Catatan Proses</strong><br>
        {{ $proyek->catatan_proses ?? '-' }}
    </div>

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
            <td width="33%">Mengetahui,<br>Orang Tua</td>
            <td width="33%" style="text-align:center;">
                {{ $sekolah->kecamatan }}, {{ $tanggal ?? date('d F Y') }}<br>
                Wali Kelas<br><br><br>
                <span class="nama">{{ $waliKelas->nama }}</span><br>
                NIPPPK : {{ $waliKelas->nipppk }}
            </td>
            <td width="33%" style="text-align:center;">
                Mengetahui<br>Kepala Sekolah<br><br><br>
                <span class="nama">{{ $pengaturan->nama_kepala_sekolah ?? 'Kepala Sekolah' }}</span><br>
    NIP : {{ $pengaturan->nip_kepala_sekolah ?? '-' }}
            </td>
        </tr>
    </table>
</body>
</html>
