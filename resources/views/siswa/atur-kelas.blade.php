<x-app-layout>
<div class="max-w-6xl mx-auto p-6">
    <h2 class="text-xl font-bold mb-4">Atur Kelas Siswa</h2>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <form action="{{ route('siswa.updateKelas') }}" method="POST">
        @csrf

        <table class="w-full border table-auto">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2">Nama Siswa</th>
                    <th class="border px-4 py-2">Email</th>
                    <th class="border px-4 py-2">Kelas Sekarang</th>
                    <th class="border px-4 py-2">Ubah Kelas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($siswa as $s)
                <tr>
                    <td class="border px-4 py-2">{{ $s->nama }}</td>
                    <td class="border px-4 py-2">{{ $s->user->email ?? '-' }}</td>
                    <td class="border px-4 py-2">{{ $s->kelas->nama ?? '-' }}</td>
                    <td class="border px-4 py-2">
                        <select name="kelas[{{ $s->id }}]" class="w-full border px-2 py-1 rounded">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelas as $k)
                                <option value="{{ $k->id }}" {{ $s->kelas_id == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4 text-right">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Simpan Perubahan
            </button>
        </div>
    </form>

    <div class="mt-4">{{ $siswa->links() }}</div>
</div>
</x-app-layout>
