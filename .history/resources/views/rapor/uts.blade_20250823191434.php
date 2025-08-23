<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Rapor Tengah Semester</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12px;
            margin: 30px;
        }

        h2 {
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        td {
            padding: 4px;
            vertical-align: top;
        }

        .biodata td {
            border: none;
        }

        .nilai th,
        .nilai td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 11px;
        }

        .nilai th {
            background: #f2f2f2;
            text-align: center;
        }

        .ttd {
            width: 100%;
            margin-top: 40px;
            font-size: 12px;
        }

        .ttd td {
            text-align: center;
            vertical-align: bottom;
            height: 80px;
        }
    </style>
</head>

<body onload="window.print()">

    <h2>LAPORAN HASIL BELAJAR TENGAH SEMESTER</h2>

    <table class="biodata">
        <tr>
            <td>Nama Peserta Didik</td>
            <td>: {{ $siswa->nama }}</td>
            <td>Kelas</td>
            <td>: {{ $kelas->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td>NISN</td>
            <td>: {{ $siswa->nisn }}</td>
            <td>No. Induk</td>
            <td>: {{ $siswa->nipd ?? '-' }}</td>
        </tr>
        <tr>
            <td>Sekolah</td>
            <td>: {{ $sekolah->nama ?? '-' }}</td>
            <td>Fase</td>
            <td>: {{ $kelas->fase->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>: {{ $sekolah->alamat ?? '-' }}</td>
            <td>Semester</td>
            <td>: {{ $semester ?? '-' }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>Tahun Ajaran</td>
            <td>: {{ $tahunAjaran ?? '-' }}</td>
        </tr>
    </table>

    {{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\rapor\uts.blade.php --}}
    @php
    $nilaiMapelById = $nilaiMapelById ?? [];
    $kategoriSebelumnya = null;
@endphp
    <table class="nilai">
        <tr>
            <th>No</th>
            <th>Mata Pelajaran</th>
            <th>Nilai Akhir</th>
            <th>Capaian Kompetensi</th>
        </tr>
        @foreach ($mapelList as $i => $mapel)
        @php
            $nilai = $nilaiMapelById[$mapel->id] ?? null;
            // Baris batas Muatan Lokal
            $isBatasMuatanLokal = $kategoriSebelumnya !== 'Muatan Lokal' && $mapel->kategori === 'Muatan Lokal';
            $kategoriSebelumnya = $mapel->kategori;
        @endphp

        {{-- Baris batas Muatan Lokal --}}
        @if($isBatasMuatanLokal)
            <tr>
                <td colspan="4" style="border-top:2px solid #000; background:#f2f2f2; font-weight:bold;">
                    {{ $i + 1 }} Muatan Lokal
                </td>
            </tr>
        @endif

        <tr>
            <td>{{ $mapel->kategori === 'Muatan Lokal' ? chr(96 + ($loop->iteration - $mapelList->where('kategori', '!=', 'Muatan Lokal')->count())) : $i + 1 }}</td>
            <td>{{ $mapel->nama }}</td>
            <td align="center">{{ $nilai?->nilai_akhir ?? '-' }}</td>
            <td>
                @if ($nilai)
                    <div>{{ $nilai->deskripsi_tertinggi ?? '-' }}</div>
                    <div>{{ $nilai->deskripsi_terendah ?? '-' }}</div>
                @else
                    -
                @endif
            </td>
        </tr>
    @endforeach
    </table>

    <table class="ttd" width="100%" style="margin-top:32px;">
        <tr>
            <td width="50%" style="text-align:center;">
                Mengetahui,<br>Orang Tua
                <br><br><br><br>
                ............
            </td>
            <td width="50%" style="text-align:center;">
                {{-- {{ $pengaturan->tempat ?? '-' }}, --}}
                {{-- {{ $pengaturan->tanggal_cetak
                    ? \Carbon\Carbon::parse($pengaturan->tanggal_cetak)->translatedFormat('d F Y')
                    : now()->translatedFormat('d F Y') }}<br> --}}
                    {{ $pengaturan->tempat ?? ($sekolah->kabupaten ?? '-') }}
                    {{ \Carbon\Carbon::parse($pengaturan->tanggal_cetak ?? now())->translatedFormat('d F Y') }}
                    <br>
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
