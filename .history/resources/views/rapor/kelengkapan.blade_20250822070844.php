{{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\rapor\kelengkapan.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelengkapan Rapor SD</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 30px;
            font-size: 12pt;
        }

        .page {
            page-break-after: always;
        }

        h1,
        h2,
        h3 {
            text-align: center;
            margin: 10px 0;
        }

        .logo {
            text-align: center;
            margin-top: 50px;
        }

        .logo img {
            width: 120px;
        }

        .biodata,
        .identitas {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .biodata td,
        .identitas td {
            padding: 5px;
            vertical-align: top;
        }

        .identitas td:first-child {
            width: 200px;
        }

        .ttd {
            margin-top: 50px;
            width: 100%;
        }

        .ttd td {
            text-align: center;
            vertical-align: bottom;
            height: 120px;
        }

        .center-page {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 70vh;
        }
    </style>
</head>
<body onload="window.print()">

{{-- <body> --}}

    <!-- HALAMAN 1 -->
    <div class="page"
        style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh;">
        <div class="logo" style="margin-bottom: 24px;">
            <img src="{{ asset('images/logo-app.png') }}" alt="Logo Sekolah">
        </div>
        <h2>RAPOR PESERTA DIDIK<br>SEKOLAH DASAR (SD)</h2>
        <br><br>
        <h3>Nama Peserta Didik:</h3>
        <h1 style="border:1px solid #000; display:inline-block; text-align:center; padding:10px 20px;">
            {{ $siswa->nama }}
        </h1>
        <h3 style="margin-top:32px;">NISN/NIPD:</h3>
        <h2 style="border:1px solid #000; display:inline-block; text-align:center;padding:10px 20px;">
            {{ $siswa->nisn }}/{{ $siswa->nipd }}
        </h2>

        <br><br><br
        <h2>KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN RI</h2>
        <h2>{{ $sekolah->nama ?? '-' }}</h2>
        <h3>{{ $sekolah->alamat ?? '-' }}</h3>
        <p style="text-align:center;">Website: {{ $sekolah->website ?? '-' }}, Email: {{ $sekolah->email ?? '-' }}</p>

    </div>

    <!-- HALAMAN 2 -->
    <div class="page">
        <h1>RAPOR<br>PESERTA DIDIK<br>SEKOLAH DASAR</h1>

        <table class="biodata">
            <tr>
                <td>Nama Sekolah</td>
                <td>: {{ $sekolah->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td>NPSN</td>
                <td>: {{ $sekolah->npsn ?? '-' }}</td>
            </tr>
            <tr>
                <td>NSS</td>
                <td>: {{ $sekolah->nss ?? '-' }}</td>
            </tr>
            <tr>
                <td>Alamat Sekolah</td>
                <td>: {{ $sekolah->alamat ?? '-' }}</td>
            </tr>
            <tr>
                <td>No. Telepon</td>
                <td>: {{ $sekolah->no_telp ?? '-' }}</td>
            </tr>
            <tr>
                <td>Desa/Kelurahan</td>
                <td>: {{ $sekolah->desa ?? '-' }}</td>
            </tr>
            <tr>
                <td>Kecamatan</td>
                <td>: {{ $sekolah->kecamatan ?? '-' }}</td>
            </tr>
            <tr>
                <td>Kabupaten/Kota</td>
                <td>: {{ $sekolah->kabupaten ?? '-' }}</td>
            </tr>
            <tr>
                <td>Provinsi</td>
                <td>: {{ $sekolah->provinsi ?? '-' }}</td>
            </tr>
            <tr>
                <td>Website</td>
                <td>: {{ $sekolah->website ?? '-' }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>: {{ $sekolah->email ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- HALAMAN 3 (IDENTITAS PESERTA DIDIK) -->
    <div class="page">
        <h2>IDENTITAS PESERTA DIDIK</h2>
        <table class="identitas">
            <tr>
                <td>1. Nama Peserta Didik</td>
                <td>: {{ $siswa->nama }}</td>
            </tr>
            <tr>
                <td>2. NISN/NIS</td>
                <td>: {{ $siswa->nisn }}/{{ $siswa->nipd }}</td>
            </tr>
            <tr>
                <td>3. Tempat, Tanggal Lahir</td>
                <td>: {{ $siswa->tempat_lahir }}, {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d F Y') }}
                </td>
            </tr>
            <tr>
                <td>4. Jenis Kelamin</td>
                <td>: {{ $siswa->jenis_kelamin }}</td>
            </tr>
            <tr>
                <td>5. Agama</td>
                <td>: {{ $siswa->agama }}</td>
            </tr>
            <tr>
                <td>6. Pendidikan Sebelumnya</td>
                <td>: {{ $siswa->pendidikan_sebelumnya ?? '-' }}</td>
            </tr>
            <tr>
                <td>7. Alamat Peserta Didik</td>
                <td>: {{ $siswa->alamat }}</td>
            </tr>
            <tr>
                <td>8. Nama Orang Tua</td>
                <td>
                    a. Ayah : {{ $siswa->nama_ayah ?? '-' }}<br>
                    b. Ibu : {{ $siswa->nama_ibu ?? '-' }}
                </td>
            </tr>
            <tr>
                <td>9. Pekerjaan Orang Tua</td>
                <td>
                    a. Ayah : {{ $siswa->pekerjaan_ayah ?? '-' }}<br>
                    b. Ibu : {{ $siswa->pekerjaan_ibu ?? '-' }}
                </td>
            </tr>
            <tr>
                <td>10. Alamat Orang Tua/Wali</td>
                <td>
                    : {{ $siswa->alamat_wali ?? '-' }}
                </td>
            </tr>
            <tr>
                <td>11. Wali Peserta Didik</td>
                <td>
                    Nama : {{ $siswa->nama_wali ?? '-' }} <br>
                    Pekerjaan : {{ $siswa->pekerjaan_wali ?? '-' }} <br>
                    {{-- Alamat : {{ $siswa->alamat_wali ?? '-' }} --}}
                </td>
            </tr>
        </table>

        <table class="ttd">
            <tr>
                <td></td>
                <td>{{ $pengaturan->tempat ?? ($sekolah->desa ?? '-') }},
                    {{ \Carbon\Carbon::parse($pengaturan->tanggal_cetak ?? now())->translatedFormat('d F Y') }}<br>Kepala
                    Sekolah,<br><br><br>
                    <b>{{ $pengaturan->nama_kepala_sekolah ?? '-' }}</b><br>
                    NIP: {{ $pengaturan->nip_kepala_sekolah ?? '-' }}
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
