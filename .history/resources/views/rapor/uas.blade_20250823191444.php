{{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\rapor\uas.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Belajar Semester</title>
    <style>
        body { font-family: "Times New Roman", Times, serif; font-size: 12px; margin: 30px; }
        h2 { text-align: center; text-transform: uppercase; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        td, th { padding: 4px; vertical-align: top; }
        .biodata td { border: none; }
        .nilai th, .nilai td { border: 1px solid #000; padding: 6px; font-size: 11px; }
        .nilai th { background: #f2f2f2; text-align: center; }
        .ekstra th, .ekstra td { border: 1px solid #000; padding: 6px; font-size: 11px; }
        .ekstra th { background: #f2f2f2; text-align: center; }
        .absensi td, .absensi th { border: 1px solid #000; padding: 6px; font-size: 11px; }
        .ttd { width: 100%; margin-top: 40px; font-size: 12px; }
        .ttd td { text-align: center; vertical-align: bottom; height: 80px; }
    </style>
</head>
<body onload="window.print()">

    <h2>LAPORAN HASIL BELAJAR SEMESTER</h2>

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

    <b>EKSTRAKURIKULER</b>
    <table class="ekstra">
        <tr>
            <th>No.</th>
            <th>Ekstrakurikuler</th>
            <th>Keterangan</th>
        </tr>
        @foreach($ekstraList as $i => $ekstra)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $ekstra->nama }}</td>
                <td>{{ $ekstra->keterangan ?? '-' }}</td>
            </tr>
        @endforeach
    </table>

    <b>KETIDAKHADIRAN</b>
    <table class="absensi">
        <tr>
            <th>Sakit</th>
            <td>{{ $absensi['sakit'] ?? 0 }} hari</td>
        </tr>
        <tr>
            <th>Izin</th>
            <td>{{ $absensi['izin'] ?? 0 }} hari</td>
        </tr>
        <tr>
            <th>Tanpa Keterangan</th>
            <td>{{ $absensi['alfa'] ?? 0 }} hari</td>
        </tr>
    </table>

    <table class="ttd" width="100%" style="margin-top:32px;">
        <tr>
            <td width="50%" style="text-align:center;">
                Mengetahui,<br>Orang Tua/Wali
                <br><br><br><br>
                ...............................
            </td>
            <td width="50%" style="text-align:center;">
                {{ $pengaturan->tempat ?? ($sekolah->kabupaten ?? '-') }},
                {{ \Carbon\Carbon::parse($pengaturan->tanggal_cetak ?? now())->translatedFormat('d F Y') }}<br>
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
