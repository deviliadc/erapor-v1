<div class="max-w-4xl mx-auto py-10">
    <h2 class="text-2xl font-bold mb-8 text-center">Pilih Peran Anda</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        {{-- Contoh peran, sesuaikan dengan kebutuhan --}}
        <a href="{{ route('dashboard.guru') }}"
            class="group block rounded-xl border border-gray-200 bg-white shadow hover:shadow-lg transition p-6 text-center">
            <img src="{{ asset('images/peran/guru.png') }}" alt="Guru"
                class="mx-auto h-24 mb-4 group-hover:scale-105 transition">
            <div class="font-semibold text-lg">Guru</div>
        </a>
        <a href="{{ route('dashboard.wali-kelas') }}"
            class="group block rounded-xl border border-gray-200 bg-white shadow hover:shadow-lg transition p-6 text-center">
            <img src="{{ asset('images/peran/wali-kelas.png') }}" alt="Wali Kelas"
                class="mx-auto h-24 mb-4 group-hover:scale-105 transition">
            <div class="font-semibold text-lg">Wali Kelas</div>
        </a>
        {{-- <a href="{{ route('dashboard.kepala-sekolah') }}"
            class="group block rounded-xl border border-gray-200 bg-white shadow hover:shadow-lg transition p-6 text-center">
            <img src="{{ asset('images/peran/kepala-sekolah.png') }}" alt="Kepala Sekolah"
                class="mx-auto h-24 mb-4 group-hover:scale-105 transition">
            <div class="font-semibold text-lg">Kepala Sekolah</div>
        </a> --}}
    </div>
</div>
