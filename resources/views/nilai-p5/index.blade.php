@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-4">Input Nilai Projek P5</h1>

    <!-- Filter Projek -->
    <form method="GET" action="{{ role_route('nilai.p5') }}" class="mb-4 flex gap-2">
        <select name="tahun_semester_id" class="border rounded p-2">
            @foreach($tahunSemesterList as $ts)
                <option value="{{ $ts->id }}" {{ request('tahun_semester_id') == $ts->id ? 'selected' : '' }}>
                    {{ $ts->tahun }} - Semester {{ $ts->semester }}
                </option>
            @endforeach
        </select>

        <select name="projek_id" class="border rounded p-2">
            @foreach($projekList as $projek)
                <option value="{{ $projek->id }}" {{ request('projek_id') == $projek->id ? 'selected' : '' }}>
                    {{ $projek->judul }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Tampilkan</button>
    </form>

    @if(isset($siswaList))
    <form action="{{ role_route('nilai.p5.simpan') }}" method="POST">
        @csrf
        <input type="hidden" name="periode" value="akhir">
        <input type="hidden" name="projek_id" value="{{ request('projek_id') }}">
        <input type="hidden" name="tahun_semester_id" value="{{ request('tahun_semester_id') }}">

        <table class="min-w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2">#</th>
                    <th class="border px-4 py-2">Nama</th>
                    <th class="border px-4 py-2">Level Capaian</th>
                    <th class="border px-4 py-2">Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($siswaList as $i => $siswa)
                <tr>
                    <td class="border px-4 py-2">{{ $i + 1 }}</td>
                    <td class="border px-4 py-2">{{ $siswa->nama }}</td>
                    <td class="border px-4 py-2">
                        <select name="nilai[{{ $siswa->id }}][level]" class="border rounded p-1">
                            <option value="">-</option>
                            <option value="Terlampaui">Terlampaui</option>
                            <option value="Sesuai">Sesuai</option>
                            <option value="Perlu Bimbingan">Perlu Bimbingan</option>
                        </select>
                    </td>
                    <td class="border px-4 py-2">
                        <input type="text" name="nilai[{{ $siswa->id }}][deskripsi]" class="w-full border rounded p-1">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded">Simpan Nilai</button>
        </div>
    </form>
    @endif
</div>
@endsection
