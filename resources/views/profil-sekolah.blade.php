<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <div class="min-h-screen bg-gray-50 font-sans">
        <!-- Header -->
        <header class="bg-white shadow sticky top-0 z-50">
            <div class="container mx-auto px-6 py-4 flex justify-between items-center">
                <nav class="flex space-x-4">
                    <a href="#tentang" class="text-gray-700 hover:text-blue-600 font-medium">Tentang</a>
                    <a href="#visi-misi" class="text-gray-700 hover:text-blue-600 font-medium">Visi & Misi</a>
                    <a href="#sejarah" class="text-gray-700 hover:text-blue-600 font-medium">Sejarah</a>
                    <a href="#galeri" class="text-gray-700 hover:text-blue-600 font-medium">Galeri</a>
                </nav>
                <a href="{{ route('login') }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Login</a>
            </div>
        </header>

        <!-- Tentang / Kepala Sekolah -->
        <section id="tentang" class="py-12 bg-white">
            <div class="container mx-auto px-6">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">Kepala Sekolah</h2>
                <div class="flex flex-col md:flex-row items-center gap-8">
                    <img src="{{ asset('images/kepala-sekolah.jpg') }}" alt="Kepala Sekolah"
                        class="w-64 rounded-xl shadow-md">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-700">Drs. H. Ahmad Sukarno</h3>
                        <p class="mt-2 text-gray-600">Selamat datang di website resmi sekolah kami. Kami berkomitmen
                            membentuk generasi yang unggul, berakhlak, dan berwawasan global.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Visi Misi -->
        <section id="visi-misi" class="py-12 bg-gray-100">
            <div class="container mx-auto px-6">
                <h2 class="text-2xl font-bold mb-4 text-gray-800">Visi</h2>
                <p class="text-gray-700 mb-6">Menjadi lembaga pendidikan yang mencetak generasi beriman, berilmu, dan
                    berdaya saing global.</p>

                <h2 class="text-2xl font-bold mb-4 text-gray-800">Misi</h2>
                <ul class="list-disc list-inside text-gray-700 space-y-2">
                    <li>Meningkatkan kualitas pendidikan berbasis karakter.</li>
                    <li>Menumbuhkan semangat belajar dan berprestasi.</li>
                    <li>Mengembangkan potensi siswa secara maksimal.</li>
                    <li>Menanamkan nilai-nilai moral dan kebangsaan.</li>
                </ul>
            </div>
        </section>

        <!-- Sejarah -->
        <section id="sejarah" class="py-12 bg-white">
            <div class="container mx-auto px-6">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">Sejarah Sekolah</h2>
                <p class="text-gray-700 leading-relaxed max-w-3xl">
                    Sekolah ini didirikan pada tahun 1985 dengan tujuan menyediakan akses pendidikan berkualitas di
                    wilayah ini.
                    Berawal dari gedung sederhana, kini telah berkembang menjadi sekolah unggulan dengan berbagai
                    prestasi akademik dan non-akademik.
                </p>
            </div>
        </section>

        <!-- Galeri / Gambar Sekolah -->
        <section id="galeri" class="py-12 bg-gray-100">
            <div class="container mx-auto px-6">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">Galeri Sekolah</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <img src="{{ asset('images/sekolah1.jpg') }}" class="rounded shadow-md" alt="Sekolah 1">
                    <img src="{{ asset('images/sekolah2.jpg') }}" class="rounded shadow-md" alt="Sekolah 2">
                    <img src="{{ asset('images/sekolah3.jpg') }}" class="rounded shadow-md" alt="Sekolah 3">
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-6 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} Nama Sekolah. All rights reserved.
        </footer>
    </div>

</body>

</html>
