<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DataMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'admin', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'kepala_sekolah', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'guru', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'siswa', 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('users')->insert([
            [
                'id' => 1,
                'username' => 'admin',
                'email' => 'sdn.darmorejo02@gmail.com',
                'password' => bcrypt('password123'),
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);

        DB::table('role_user')->insert([
            [
                'id' => 1,
                'user_id' => 1,
                'role_id' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);

        // DB::table('tahun_semester')->insert([
        //     ['id' => 1, 'tahun' => '2021/2022', 'semester' => 'Ganjil', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
        //     ['id' => 2, 'tahun' => '2021/2022', 'semester' => 'Genap', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
        //     ['id' => 3, 'tahun' => '2022/2023', 'semester' => 'Ganjil', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
        //     ['id' => 4, 'tahun' => '2022/2023', 'semester' => 'Genap', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
        //     ['id' => 5, 'tahun' => '2023/2024', 'semester' => 'Ganjil', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
        //     ['id' => 6, 'tahun' => '2023/2024', 'semester' => 'Genap', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
        //     ['id' => 7, 'tahun' => '2024/2025', 'semester' => 'Ganjil', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
        //     ['id' => 8, 'tahun' => '2024/2025', 'semester' => 'Genap', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
        //     ['id' => 9, 'tahun' => '2025/2026', 'semester' => 'Ganjil', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        //     ['id' => 10, 'tahun' => '2025/2026', 'semester' => 'Genap', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
        // ]);

        // DB::table('tahun_ajaran')->insert([
        //     ['id' => 1, 'tahun' => '2021/2022', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
        //     ['id' => 2, 'tahun' => '2022/2023', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
        //     ['id' => 3, 'tahun' => '2023/2024', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
        //     ['id' => 4, 'tahun' => '2024/2025', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
        //     ['id' => 5, 'tahun' => '2025/2026', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        // ]);

        // DB::table('tahun_semester')->insert([
        //     ['id' => 1, 'tahun_ajaran_id' => 1, 'semester' => 'Ganjil', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
        //     ['id' => 2, 'tahun_ajaran_id' => 1, 'semester' => 'Genap', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
        //     ['id' => 3, 'tahun_ajaran_id' => 2, 'semester' => 'Ganjil', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
        //     ['id' => 4, 'tahun_ajaran_id' => 2, 'semester' => 'Genap', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
        //     ['id' => 5, 'tahun_ajaran_id' => 3, 'semester' => 'Ganjil', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
        //     ['id' => 6, 'tahun_ajaran_id' => 3, 'semester' => 'Genap', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
        //     ['id' => 7, 'tahun_ajaran_id' => 4, 'semester' => 'Ganjil', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
        //     ['id' => 8, 'tahun_ajaran_id' => 4, 'semester' => 'Genap', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
        //     ['id' => 9, 'tahun_ajaran_id' => 5, 'semester' => 'Ganjil', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        //     ['id' => 10, 'tahun_ajaran_id' => 5, 'semester' => 'Genap', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
        // ]);

        $now = now();

DB::table('tahun_ajaran')->insert([
    ['id' => 1, 'tahun' => '2021/2022', 'mulai' => '2021-07-01', 'selesai' => '2022-06-30', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
    ['id' => 2, 'tahun' => '2022/2023', 'mulai' => '2022-07-01', 'selesai' => '2023-06-30', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
    ['id' => 3, 'tahun' => '2023/2024', 'mulai' => '2023-07-01', 'selesai' => '2024-06-30', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
    ['id' => 4, 'tahun' => '2024/2025', 'mulai' => '2024-07-01', 'selesai' => '2025-06-30', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
    ['id' => 5, 'tahun' => '2025/2026', 'mulai' => '2025-07-01', 'selesai' => '2026-06-30', 'is_active' => true,  'created_at' => $now, 'updated_at' => $now],
]);

DB::table('tahun_semester')->insert([
    ['id' => 1, 'tahun_ajaran_id' => 1, 'semester' => 'Ganjil', 'mulai' => '2021-07-01', 'selesai' => '2021-12-31', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
    ['id' => 2, 'tahun_ajaran_id' => 1, 'semester' => 'Genap', 'mulai' => '2022-01-01', 'selesai' => '2022-06-30', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
    ['id' => 3, 'tahun_ajaran_id' => 2, 'semester' => 'Ganjil', 'mulai' => '2022-07-01', 'selesai' => '2022-12-31', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
    ['id' => 4, 'tahun_ajaran_id' => 2, 'semester' => 'Genap', 'mulai' => '2023-01-01', 'selesai' => '2023-06-30', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
    ['id' => 5, 'tahun_ajaran_id' => 3, 'semester' => 'Ganjil', 'mulai' => '2023-07-01', 'selesai' => '2023-12-31', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
    ['id' => 6, 'tahun_ajaran_id' => 3, 'semester' => 'Genap', 'mulai' => '2024-01-01', 'selesai' => '2024-06-30', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
    ['id' => 7, 'tahun_ajaran_id' => 4, 'semester' => 'Ganjil', 'mulai' => '2024-07-01', 'selesai' => '2024-12-31', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
    ['id' => 8, 'tahun_ajaran_id' => 4, 'semester' => 'Genap', 'mulai' => '2025-01-01', 'selesai' => '2025-06-30', 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
    ['id' => 9, 'tahun_ajaran_id' => 5, 'semester' => 'Ganjil', 'mulai' => '2025-07-01', 'selesai' => '2025-12-31', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
    ['id' => 10,'tahun_ajaran_id' => 5, 'semester' => 'Genap','mulai' => '2026-01-01','selesai' => '2026-06-30','is_active' => false,'created_at' => $now,'updated_at' => $now],
]);


        DB::table('fase')->insert([
            ['id' => 1, 'nama' => 'Fase A', 'keterangan' => 'Kelas 1 - 2', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'nama' => 'Fase B', 'keterangan' => 'Kelas 3 - 4', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'nama' => 'Fase C', 'keterangan' => 'Kelas 5 - 6', 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('kelas')->insert([
            ['id' => 1, 'nama' => '1', 'fase_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'nama' => '2', 'fase_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'nama' => '3', 'fase_id' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'nama' => '4', 'fase_id' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'nama' => '5', 'fase_id' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'nama' => '6', 'fase_id' => 3, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('mapel')->insert([
            [
                'id' => 1,
                'kode_mapel' => 'PAI',
                'nama' => 'Pendidikan Agama Islam',
                'kategori' => 'Wajib',
                'agama' => 'Islam',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'kode_mapel' => 'PPKn',
                'nama' => 'Pendidikan Pancasila dan Kewarganegaraan',
                'kategori' => 'Wajib',
                'agama' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'kode_mapel' => 'BINDO',
                'nama' => 'Bahasa Indonesia',
                'kategori' => 'Wajib',
                'agama' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'kode_mapel' => 'MTK',
                'nama' => 'Matematika',
                'kategori' => 'Wajib',
                'agama' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'kode_mapel' => 'SR',
                'nama' => 'Seni Rupa',
                'kategori' => 'Wajib',
                'agama' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 6,
                'kode_mapel' => 'PJOK',
                'nama' => 'Pendidikan Jasmani dan Olahraga',
                'kategori' => 'Wajib',
                'agama' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 7,
                'kode_mapel' => 'BING',
                'nama' => 'Bahasa Inggris',
                'kategori' => 'Wajib',
                'agama' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 8,
                'kode_mapel' => 'BJW',
                'nama' => 'Bahasa Jawa',
                'kategori' => 'Muatan Lokal',
                'agama' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 9,
                'kode_mapel' => 'PLH',
                'nama' => 'Pendidikan Lingkungan Hidup',
                'kategori' => 'Muatan Lokal',
                'agama' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('bab')->insert([
            ['id' => 1, 'nama' => 'Bab 1', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'nama' => 'Bab 2', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'nama' => 'Bab 3', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'nama' => 'Bab 4', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'nama' => 'Bab 5', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'nama' => 'Bab 6', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'nama' => 'Bab 7', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 8, 'nama' => 'Bab 8', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 9, 'nama' => 'Bab 9', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 10, 'nama' => 'Bab 10', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 11, 'nama' => 'Bab 11', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 12, 'nama' => 'Bab 12', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 13, 'nama' => 'Bab 13', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 14, 'nama' => 'Bab 14', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 15, 'nama' => 'Bab 15', 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('ekstra')->insert([
            ['id' => 1, 'nama' => 'Pramuka', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'nama' => 'Bahasa Inggris', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'nama' => 'Menari', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'nama' => 'BTQ', 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('param_ekstra')->insert([
            // Pramuka
            ['ekstra_id' => 1, 'parameter' => 'Lafal Dwi Satya', 'created_at' => $now, 'updated_at' => $now],
            ['ekstra_id' => 1, 'parameter' => 'Lafal Dwi Dharma', 'created_at' => $now, 'updated_at' => $now],
            ['ekstra_id' => 1, 'parameter' => 'Lafal Pancasila', 'created_at' => $now, 'updated_at' => $now],
            ['ekstra_id' => 1, 'parameter' => 'Lagu Kebangsaan', 'created_at' => $now, 'updated_at' => $now],
            ['ekstra_id' => 1, 'parameter' => 'Tepuk Pramuka', 'created_at' => $now, 'updated_at' => $now],
            ['ekstra_id' => 1, 'parameter' => 'Salam Pramuka', 'created_at' => $now, 'updated_at' => $now],

            // Bahasa Inggris
            ['ekstra_id' => 2, 'parameter' => 'Good Morning', 'created_at' => $now, 'updated_at' => $now],
            ['ekstra_id' => 2, 'parameter' => "Let's Count", 'created_at' => $now, 'updated_at' => $now],
            ['ekstra_id' => 2, 'parameter' => 'Colorful', 'created_at' => $now, 'updated_at' => $now],

            // Menari
            ['ekstra_id' => 3, 'parameter' => 'Hafalan Gerak', 'created_at' => $now, 'updated_at' => $now],
            ['ekstra_id' => 3, 'parameter' => 'Keserasian Gerak', 'created_at' => $now, 'updated_at' => $now],
            ['ekstra_id' => 3, 'parameter' => 'Ekspresi Gerak', 'created_at' => $now, 'updated_at' => $now],

            // BTQ
            ['ekstra_id' => 4, 'parameter' => 'Huruf Hijayah', 'created_at' => $now, 'updated_at' => $now],
            ['ekstra_id' => 4, 'parameter' => 'Doa Sehari-hari', 'created_at' => $now, 'updated_at' => $now],
            ['ekstra_id' => 4, 'parameter' => 'Menulis Basmalah', 'created_at' => $now, 'updated_at' => $now],
            ['ekstra_id' => 4, 'parameter' => 'Hafalan', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Insert P5 Dimensi, Elemen, Sub Elemen via query builder agar rapi
        $this->insertP5();
    }

    protected function insertP5()
    {
        $now = now();

        // Dimensi
        $dimensi = [
            [1, 'Beriman dan Bertakwa kepada Tuhan YME', 'Memiliki keyakinan, integritas, dan toleransi beragama.'],
            [2, 'Berkebinekaan Global', 'Menghargai keragaman budaya dan aktif dalam perdamaian dunia.'],
            [3, 'Gotong Royong', 'Bersikap kolaboratif dan empatik.'],
            [4, 'Mandiri', 'Tanggung jawab terhadap diri dan proses belajarnya.'],
            [5, 'Bernalar Kritis', 'Menganalisis informasi secara objektif dan logis.'],
            [6, 'Kreatif', 'Menghasilkan gagasan dan karya orisinal.'],
        ];
        foreach ($dimensi as [$id, $nama, $deskripsi]) {
            DB::table('p5_dimensi')->insert([
                'id' => $id,
                'nama_dimensi' => $nama,
                'deskripsi' => $deskripsi,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Elemen
        $elemen = [
            // Dimensi 1
            [1, 'Akhlak Beragama'],
            [1, 'Akhlak pribadi'],
            [1, 'Akhlak Kepada Manusia'],
            [1, 'Akhlak Kepada Alam'],
            [1, 'Akhlak Bernegara'],
            // Dimensi 2
            [2, 'Mengenal dan menghargai budaya'],
            [2, 'Komunikasi dan interaksi antar budaya'],
            [2, 'Refleksi dan bertanggung jawab terhadap pengalaman kebinekaan'],
            [2, 'Berkeadilan sosial'],
            // Dimensi 3
            [3, 'Kolaborasi'],
            [3, 'Kepedulian'],
            [3, 'Berbagi'],
            // Dimensi 4
            [4, 'Pemahaman diri dan situasi yang dihadapi'],
            [4, 'Regulasi diri'],
            // Dimensi 5
            [5, 'Memperoleh dan memproses informasi dan gagasan'],
            [5, 'Menganalisis dan mengevaluasi penalaran dan prosedurnya'],
            [5, 'Refleksi pemikiran dan proses berpikir'],
            // Dimensi 6
            [6, 'Menghasilkan gagasan yang orisinal'],
            [6, 'Menghasilkan karya dan tindakan yang orisinal'],
            [6, 'Memiliki keluwesan berpikir dalam mencari alternatif solusi permasalahan'],
        ];
        foreach ($elemen as [$dimensiId, $nama]) {
            DB::table('p5_elemen')->insert([
                'p5_dimensi_id' => $dimensiId,
                'nama_elemen' => $nama,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Sub Elemen
        $subElemen = [
            // Elemen 1
            [1, 'Melakukan ibadah sesuai ajaran agama'],
            [1, 'Mengenal dan Mencintai Tuhan Yang Maha Esa'],
            [1, 'Pemahaman Agama/Kepercayaan'],
            // Elemen 2
            [2, 'Integritas'],
            [2, 'Merawat Diri secara Fisik, Mental dan Spiritual'],
            // Elemen 3
            [3, 'Mengutamakan persamaan dengan orang lain dan menghargai perbedaan'],
            [3, 'Berempati kepada orang lain'],
            // Elemen 4
            [4, 'Memahami Keterhubungan Ekosistem Bumi'],
            [4, 'Menjaga Lingkungan Alam Sekitar'],
            // Elemen 5
            [5, 'Melaksanakan Hak dan Kewajiban sebagai Warga Negara Indonesia'],
            // Elemen 6
            [6, 'Mendalami budaya dan identitas budaya'],
            [6, 'Mengeksplorasi dan membandingkan pengetahuan budaya, kepercayaan, serta praktiknya'],
            [6, 'Menumbuhkan rasa menghormati terhadap keanekaragaman budaya'],
            // Elemen 7
            [7, 'Berkomunikasi antar budaya'],
            [7, 'Mempertimbangkan dan menumbuhkan berbagai perspektif'],
            // Elemen 8
            [8, 'Refleksi terhadap pengalaman kebinekaan'],
            [8, 'Menghilangkan stereotip dan prasangka'],
            [8, 'Menyelaraskan perbedaan budaya'],
            // Elemen 9
            [9, 'Aktif membangun masyarakat yang inklusif, adil, dan berkelanjutan'],
            [9, 'Berpartisipasi dalam proses pengambilan keputusan bersama'],
            [9, 'Memahami peran individu dalam demokrasi'],
            // Elemen 10
            [10, 'Kerja sama'],
            [10, 'Komunikasi untuk mencapai tujuan bersama'],
            [10, 'Saling-ketergantungan positif'],
            [10, 'Koordinasi Sosial'],
            // Elemen 11
            [11, 'Tanggap terhadap lingkungan Sosial'],
            [11, 'Persepsi sosial'],
            // Elemen 12
            [12, 'Berbagi'],
            // Elemen 13
            [13, 'Mengenali kualitas dan minat diri serta tantangan yang dihadapi'],
            [13, 'Mengembangkan refleksi diri'],
            // Elemen 14
            [14, 'Regulasi emosi'],
            [14, 'Penetapan tujuan belajar, prestasi, dan pengembangan diri serta rencana strategis untuk mencapainya'],
            [14, 'Menunjukkan inisiatif dan bekerja secara mandiri'],
            [14, 'Mengembangkan pengendalian dan disiplin diri'],
            [14, 'Percaya diri, tangguh (resilient), dan adaptif'],
            // Elemen 15
            [15, 'Mengajukan pertanyaan'],
            [15, 'Mengidentifikasi, mengklarifikasi, dan mengolah informasi dan gagasan'],
            // Elemen 16
            [16, 'Menganalisis dan mengevaluasi penalaran dan prosedurnya'],
            // Elemen 17
            [17, 'Merefleksi dan mengevaluasi pemikirannya sendiri'],
            // Elemen 18
            [18, 'Menghasilkan gagasan yang orisinal'],
            // Elemen 19
            [19, 'Menghasilkan karya dan tindakan yang orisinal'],
            // Elemen 20
            [20, 'Memiliki keluwesan berpikir dalam mencari alternatif solusi permasalahan'],
        ];
        foreach ($subElemen as [$elemenId, $nama]) {
            DB::table('p5_sub_elemen')->insert([
                'p5_elemen_id' => $elemenId,
                'nama_sub_elemen' => $nama,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Capaian P5
        $capaian = [
            // Subelemen 1
            [1, 1, 'Terbiasa melaksanakan ibadah sesuai ajaran agama/ kepercayaannya.'],
            [2, 1, 'Terbiasa melaksanakan ibadah wajib sesuai tuntunan agama/ kepercayaannya.'],
            [3, 1, 'Melaksanakan ibadah secara rutin sesuai dengan tuntunan agama/kepercayaan, berdoa mandiri, merayakan, dan memahami makna harihari besar.'],
            // Subelemen 2
            [1, 2, 'Mengenal sifat-sifat utama Tuhan Yang Maha Esa bahwa Dia adalah Sang Pencipta yang Maha Pengasih dan Maha Penyayang dan mengenali kebaikan dirinya sebagai cerminan sifat Tuhan.'],
            [2, 2, 'Memahami sifat-sifat Tuhan utama lainnya dan mengaitkan sifat-sifat tersebut dengan konsep dirinya dan ciptaan-Nya.'],
            [3, 2, 'Memahami berbagai kualitas atau sifat-sifat Tuhan Yang Maha Esa yang diutarakan dalam kitab suci agama masing-masing dan menghubungkan kualitas-kualitas positif Tuhan dengan sikap pribadinya, serta meyakini firman Tuhan sebagai kebenaran.'],
            // Subelemen 3
            [1, 3, 'Mengenal unsur-unsur utama agama/kepercayaan (ajaran, ritual keagamaan, kitab suci, dan orang suci/utusan Tuhan YME).'],
            [2, 3, 'Mengenal unsur-unsur utama agama/kepercayaan (simbol-simbol keagamaan dan sejarah agama/kepercayaan).'],
            [3, 3, 'Memahami unsur-unsur utama agama/kepercayaan, dan mengenali peran agama/kepercayaan dalam kehidupan serta memahami ajaran moral agama.'],
            // Subelemen 4
            [1, 4, 'Membiasakan bersikap jujur terhadap diri sendiri dan orang lain dan berani menyampaikan kebenaran atau fakta.'],
            [2, 4, 'Membiasakan melakukan refleksi tentang pentingnya bersikap jujur dan berani menyampaikan kebenaran atau fakta.'],
            [3, 4, 'Berani dan konsisten menyampaikan kebenaran atau fakta serta memahami konsekuensi-konsekuensinya untuk diri sendiri.'],
            // Subelemen 5
            [1, 5, 'Memiliki rutinitas sederhana yang diatur secara mandiri dan dijalankan sehari-hari serta menjaga kesehatan dan keselamatan/keamanan diri dalam semua aktivitas kesehariannya.'],
            [2, 5, 'Mulai membiasakan diri untuk disiplin, rapi, membersihkan dan merawat tubuh, menjaga tingkah laku dan perkataan dalam semua aktivitas kesehariannya.'],
            [3, 5, 'Memperhatikan kesehatan jasmani, mental, dan rohani dengan melakukan aktivitas fisik, sosial, dan ibadah.'],
            // Subelemen 6
            [1, 6, 'Mengenali hal-hal yang sama dan berbeda yang dimiliki diri dan temannya dalam berbagai hal, serta memberikan respons secara positif.'],
            [2, 6, 'Terbiasa mengidentifikasi hal-hal yang sama dan berbeda yang dimiliki diri dan temannya dalam berbagai hal serta memberikan respons secara positif.'],
            [3, 6, 'Mengidentifikasi kesamaan dengan orang lain sebagai perekat hubungan sosial dan mewujudkannya dalam aktivitas kelompok. Mulai mengenal berbagai kemungkinan interpretasi dan cara pandang yang berbeda ketika dihadapkan dengan dilema.'],
            // Subelemen 7
            [1, 7, 'Mengidentifikasi emosi, minat, dan kebutuhan orang-orang terdekat dan meresponsnya secara positif.'],
            [2, 7, 'Terbiasa memberikan apresiasi di lingkungan sekolah dan masyarakat.'],
            [3, 7, 'Mulai memandang sesuatu dari perspektif orang lain serta mengidentifikasi kebaikan dan kelebihan orang sekitarnya.'],
            // Subelemen 8
            [1, 8, 'Mengidentifikasi berbagai ciptaan Tuhan.'],
            [2, 8, 'Memahami keterhubungan antara satu ciptaan dengan ciptaan Tuhan yang lainnya.'],
            [3, 8, 'Memahami konsep harmoni dan mengidentifikasi adanya saling kebergantungan antara berbagai ciptaan Tuhan.'],
            // Subelemen 9
            [1, 9, 'Membiasakan bersyukur atas lingkungan alam sekitar dan berlatih untuk menjaganya.'],
            [2, 9, 'Terbiasa memahami tindakan-tindakan yang ramah dan tidak ramah lingkungan serta membiasakan diri untuk berperilaku ramah lingkungan.'],
            [3, 9, 'Mewujudkan rasa syukur dengan terbiasa berperilaku ramah lingkungan dan memahami akibat perbuatan tidak ramah lingkungan dalam lingkup kecil maupun besar.'],
            // Subelemen 10
            [1, 10, 'Mengidentifikasi hak dan tanggung jawabnya di rumah, sekolah, dan lingkungan sekitar serta kaitannya dengan keimanan kepada Tuhan YME.'],
            [2, 10, 'Mengidentifikasi hak dan tanggung jawab orang-orang di sekitarnya serta kaitannya dengan keimanan kepada Tuhan YME.'],
            [3, 10, 'Mengidentifikasi dan memahami peran, hak, dan kewajiban dasar sebagai warga negara serta kaitannya dengan keimanan kepada Tuhan YME dan secara sadar mempraktikkannya dalam kehidupan sehari-hari.'],
            // Subelemen 11
            [1, 11, 'Mengidentifikasi dan mendeskripsikan ideide tentang dirinya dan beberapa kelompok di lingkungan sekitarnya.'],
            [2, 11, 'Mengidentifikasi dan mendeskripsikan ide-ide tentang dirinya dan berbagai kelompok di lingkungan sekitarnya, serta cara orang lain berperilaku dan berkomunikasi dengannya.'],
            [3, 11, 'Mengidentifikasi dan mendeskripsikan keragaman budaya di sekitarnya; serta menjelaskan peran budaya dan bahasa dalam membentuk identitas dirinya.'],
            // Subelemen 12
            [1, 12, 'Mengidentifikasi dan mendeskripsikan praktik keseharian diri dan budayanya.'],
            [2, 12, 'Mengidentifikasi dan membandingkan praktik keseharian diri dan budayanya dengan orang lain di tempat dan waktu/era yang berbeda.'],
            [3, 12, 'Mendeskripsikan dan membandingkan pengetahuan, kepercayaan, dan praktik dari berbagai kelompok budaya.'],
            // Subelemen 13
            [1, 13, 'Mendeskripsikan pengalaman dan pemahaman hidup bersama-sama dalam kemajemukan.'],
            [2, 13, 'Memahami bahwa kemajemukan dapat memberikan kesempatan untuk memperoleh pengalaman dan pemahaman yang baru.'],
            [3, 13, 'Mengidentifikasi peluang dan tantangan yang muncul dari keragaman budaya di Indonesia.'],
            // Subelemen 14
            [1, 14, 'Mengenali bahwa diri dan orang lain menggunakan kata, gambar, dan bahasa tubuh yang dapat memiliki makna yang berbeda di lingkungan sekitarnya.'],
            [2, 14, 'Mendeskripsikan penggunaan kata, tulisan dan bahasa tubuh yang memiliki makna yang berbeda di lingkungan sekitarnya dan dalam suatu budaya tertentu.'],
            [3, 14, 'Memahami persamaan dan perbedaan cara komunikasi baik di dalam maupun antarkelompok budaya.'],
            // Subelemen 15
            [1, 15, 'Mengekspresikan pandangannya terhadap topik yang umum dan mendengarkan sudut pandang orang lain yang berbeda dari dirinya dalam lingkungan keluarga dan sekolah.'],
            [2, 15, 'Mengekspresikan pandangannya terhadap topik yang umum dan dapat mengenal sudut pandang orang lain. Mendengarkan dan memperkirakan sudut pandang orang lain yang berbeda dari dirinya pada situasi di ranah sekolah, keluarga, dan lingkungan sekitar.'],
            [3, 15, 'Membandingkan beragam perspektif untuk memahami permasalahan seharihari. Memperkirakan dan mendeskripsikan situasi komunitas yang berbeda dengan dirinya ke dalam situasi dirinya dalam konteks lokal dan regional.'],
            // Subelemen 16
            [1, 16, 'Menyebutkan apa yang telah dipelajari tentang orang lain dari interaksinya dengan kemajemukan budaya di lingkungan sekolah dan rumah.'],
            [2, 16, 'Menyebutkan apa yang telah dipelajari tentang orang lain dari interaksinya dengan kemajemukan budaya di lingkungan sekitar.'],
            [3, 16, 'Menjelaskan apa yang telah dipelajari dari interaksi dan pengalaman dirinya dalam lingkungan yang beragam.'],
            // Subelemen 17
            [1, 17, 'Mengenali perbedaan tiap orang atau kelompok dan menyikapinya sebagai kewajaran.'],
            [2, 17, 'Mengkonfirmasi dan mengklarifikasi stereotip dan prasangka yang dimilikinya tentang orang atau kelompok di sekitarnya untuk mendapatkan pemahaman yang lebih baik.'],
            [3, 17, 'Mengkonfirmasi dan mengklarifikasi stereotip dan prasangka yang dimilikinya tentang orang atau kelompok di sekitarnya untuk mendapatkan pemahaman yang lebih baik serta mengidentifikasi pengaruhnya terhadap individu dan kelompok di lingkungan sekitarnya.'],
            // Subelemen 18
            [1, 18, 'Mengidentifikasi perbedaan budaya yang konkret di lingkungan sekitar.'],
            [2, 18, 'Mengenali bahwa perbedaan budaya mempengaruhi pemahaman antarindividu.'],
            [3, 18, 'Mencari titik temu nilai budaya yang beragam untuk menyelesaikan permasalahan bersama.'],
            // Subelemen 19
            [1, 19, 'Menjalin pertemanan tanpa memandang perbedaan agama, suku, ras, jenis kelamin, dan perbedaan lainnya, dan mengenal masalah-masalah sosial, ekonomi, dan lingkungan di lingkungan sekitarnya.'],
            [2, 19, 'Mengidentifikasi cara berkontribusi terhadap lingkungan sekolah, rumah dan lingkungan sekitarnya yang inklusif, adil dan berkelanjutan.'],
            [3, 19, 'Membandingkan beberapa tindakan dan praktik perbaikan lingkungan sekolah yang inklusif, adil, dan berkelanjutan, dengan mempertimbangkan dampaknya secara jangka panjang terhadap manusia, alam, dan masyarakat.'],
            // Subelemen 20
            [1, 20, 'Mengidentifikasi pilihan-pilihan berdasarkan kebutuhan dirinya dan orang lain ketika membuat keputusan.'],
            [2, 20, 'Berpartisipasi menentukan beberapa pilihan untuk keperluan bersama berdasarkan kriteria sederhana.'],
            [3, 20, 'Berpartisipasi dalam menentukan kriteria yang disepakati bersama untuk menentukan pilihan dan keputusan untuk kepentingan bersama.'],
            // Subelemen 21
            [1, 21, 'Mengidentifikasi peran, hak dan kewajiban warga dalam masyarakat demokratis.'],
            [2, 21, 'Memahami konsep hak dan kewajiban, serta implikasinya terhadap perilakunya.'],
            [3, 21, 'Memahami konsep hak dan kewajiban, serta implikasinya terhadap perilakunya. Menggunakan konsep ini untuk menjelaskan perilaku diri dan orang sekitarnya.'],
            // Subelemen 22
            [1, 22, 'Menerima dan melaksanakan tugas serta peran yang diberikan kelompok dalam sebuah kegiatan bersama.'],
            [2, 22, 'Menampilkan tindakan yang sesuai dengan harapan dan tujuan kelompok.'],
            [3, 22, 'Menunjukkan ekspektasi (harapan) positif kepada orang lain dalam rangka mencapai tujuan kelompok di lingkungan sekitar (sekolah dan rumah).'],
            // Subelemen 23
            [1, 23, 'Memahami informasi sederhana dari orang lain dan menyampaikan informasi sederhana kepada orang lain menggunakan katakatanya sendiri.'],
            [2, 23, 'Memahami informasi yang disampaikan (ungkapan pikiran, perasaan, dan keprihatinan) orang lain dan menyampaikan informasi secara akurat menggunakan berbagai simbol dan media.'],
            [3, 23, 'Memahami informasi dari berbagai sumber dan menyampaikan pesan menggunakan berbagai simbol dan media secara efektif kepada orang lain untuk mencapai tujuan bersama.'],
            // Subelemen 24
            [1, 24, 'Mengenali kebutuhankebutuhan diri sendiri yang memerlukan orang lain dalam pemenuhannya.'],
            [2, 24, 'Menyadari bahwa setiap orang membutuhkan orang lain dalam memenuhi kebutuhannya dan perlunya saling membantu.'],
            [3, 24, 'Menyadari bahwa meskipun setiap orang memiliki otonominya masingmasing, setiap orang membutuhkan orang lain dalam memenuhi kebutuhannya.'],
            // Subelemen 25
            [1, 25, 'Melaksanakan aktivitas kelompok sesuai dengan kesepakatan bersama dengan bimbingan, dan saling mengingatkan adanya kesepakatan tersebut.'],
            [2, 25, 'Menyadari bahwa dirinya memiliki peran yang berbeda dengan orang lain/temannya, serta mengetahui konsekuensi perannya terhadap ketercapaian tujuan.'],
            [3, 25, 'Menyelaraskan tindakannya sesuai dengan perannya dan mempertimbangkan peran orang lain untuk mencapai tujuan bersama.'],
            // Subelemen 26
            [1, 26, 'Peka dan mengapresiasi orangorang di lingkungan sekitar, kemudian melakukan tindakan sederhana untuk mengungkapkannya.'],
            [2, 26, 'Peka dan mengapresiasi orangorang di lingkungan sekitar, kemudian melakukan tindakan untuk menjaga keselarasan dalam berelasi dengan orang lain.'],
            [3, 26, 'Tanggap terhadap lingkungan sosial sesuai dengan tuntutan peran sosialnya dan menjaga keselarasan dalam berelasi dengan orang lain.'],
            // Subelemen 27
            [1, 27, 'Mengenali berbagai reaksi orang lain di lingkungan sekitar dan penyebabnya.'],
            [2, 27, 'Memahami berbagai alasan orang lain menampilkan respon tertentu.'],
            [3, 27, 'Menerapkan pengetahuan mengenai berbagai reaksi orang lain dan penyebabnya dalam konteks keluarga, sekolah, serta pertemanan dengan sebaya.'],
            // Subelemen 28
            [1, 28, 'Memberi dan menerima hal yang dianggap berharga dan penting kepada/dari orangorang di lingkungan sekitar.'],
            [2, 28, 'Memberi dan menerima hal yang dianggap penting dan berharga kepada/dari orangorang di lingkungan sekitar baik yang dikenal maupun tidak dikenal.'],
            [3, 28, 'Memberi dan menerima hal yang dianggap penting dan berharga kepada/dari orangorang di lingkungan luas/masyarakat baik yang dikenal maupun tidak dikenal.'],
            // Subelemen 29
            [1, 29, 'Mengidentifikasi dan menggambarkan kemampuan, prestasi, dan ketertarikannya secara subjektif.'],
            [2, 29, 'Mengidentifikasi kemampuan, prestasi, dan ketertarikannya serta tantangan yang dihadapi berdasarkan kejadian-kejadian yang dialaminya dalam kehidupan sehari-hari.'],
            [3, 29, 'Menggambarkan pengaruh kualitas dirinya terhadap pelaksanaan dan hasil belajar; serta mengidentifikasi kemampuan yang ingin dikembangkan dengan mempertimbangkan tantangan yang dihadapinya dan umpan balik dari orang dewasa.'],
            // Subelemen 30
            [1, 30, 'Melakukan refleksi untuk mengidentifikasi kekuatan dan kelemahan, serta prestasi dirinya.'],
            [2, 30, 'Melakukan refleksi untuk mengidentifikasi kekuatan, kelemahan, dan prestasi dirinya, serta situasi yang dapat mendukung dan menghambat pembelajaran dan pengembangan dirinya.'],
            [3, 30, 'Melakukan refleksi untuk mengidentifikasi faktor-faktor di dalam maupun di luar dirinya yang dapat mendukung/ menghambatnya dalam belajar dan mengembangkan diri; serta mengidentifikasi cara-cara untuk mengatasi kekurangannya.'],
            // Subelemen 31
            [1, 31, 'Mengidentifikasi perbedaan emosi yang dirasakannya dan situasi-situasi yang menyebabkan-nya; serta mengekspresikan secara wajar.'],
            [2, 31, 'Mengetahui adanya pengaruh orang lain, situasi, dan peristiwa yang terjadi terhadap emosi yang dirasakannya; serta berupaya untuk mengekspresikan emosi secara tepat dengan mempertimbangkan perasaan dan kebutuhan orang lain disekitarnya.'],
            [3, 31, 'Memahami perbedaan emosi yang dirasakan dan dampaknya terhadap proses belajar dan interaksinya dengan orang lain; serta mencoba caracara yang sesuai untuk mengelola emosi agar dapat menunjang aktivitas belajar dan interaksinya dengan orang lain.'],
            // Subelemen 32
            [1, 32, 'Menetapkan target belajar dan merencanakan waktu dan tindakan belajar yang akan dilakukannya.'],
            [2, 32, 'Menjelaskan pentingnya memiliki tujuan dan berkomitmen dalam mencapainya serta mengeksplorasi langkah-langkah yang sesuai untuk mencapainya.'],
            [3, 32, 'Menilai faktor-faktor (kekuatan dan kelemahan) yang ada pada dirinya dalam upaya mencapai tujuan belajar, prestasi, dan pengembangan dirinya serta mencoba berbagai strategi untuk mencapainya.'],
            // Subelemen 33
            [1, 33, 'Berinisiatif untuk mengerjakan tugastugas rutin secara mandiri dibawah pengawasan dan dukungan orang dewasa.'],
            [2, 33, 'Mempertimbangkan, memilih dan mengadopsi berbagai strategi dan mengidentifikasi sumber bantuan yang diperlukan serta berinisiatif menjalankannya untuk mendapatkan hasil belajar yang diinginkan.'],
            [3, 33, 'Memahami arti penting bekerja secara mandiri serta inisiatif untuk melakukannya dalam menunjang pembelajaran dan pengembangan dirinya.'],
            // Subelemen 34
            [1, 34, 'Melaksanakan kegiatan belajar di kelas dan menyelesaikan tugastugas dalam waktu yang telah disepakati.'],
            [2, 34, 'Menjelaskan pentingnya mengatur diri secara mandiri dan mulai menjalankan kegiatan dan tugas yang telah sepakati secara mandiri.'],
            [3, 34, 'Mengidentifikasi faktor-faktor yang dapat mempengaruhi kemampuan dalam mengelola diri dalam pelaksanaan aktivitas belajar dan pengembangan dirinya.'],
            // Subelemen 35
            [1, 35, 'Berani mencoba dan adaptif menghadapi situasi baru serta bertahan mengerjakan tugas-tugas yang disepakati hingga tuntas.'],
            [2, 35, 'Tetap bertahan mengerjakan tugas ketika dihadapkan dengan tantangan dan berusaha menyesuaikan strateginya ketika upaya sebelumnya tidak berhasil.'],
            [3, 35, 'Menyusun, menyesuaikan, dan mengujicobakan berbagai strategi dan cara kerjanya untuk membantu dirinya dalam penyelesaian tugas yang menantang.'],
            // Subelemen 36
            [1, 36, 'Mengajukan pertanyaan untuk menjawab keingintahuannya dan untuk mengidentifikasi suatu permasalahan mengenai dirinya dan lingkungan sekitarnya.'],
            [2, 36, 'Mengajukan pertanyaan untuk mengidentifikasi suatu permasalahan dan mengkonfirmasi pemahaman terhadap suatu permasalahan mengenai dirinya dan lingkungan sekitarnya.'],
            [3, 36, 'Mengajukan pertanyaan untuk membandingkan berbagai informasi dan untuk menambah pengetahuannya.'],
            // Subelemen 37
            [1, 37, 'Mengidentifikasi dan mengolah informasi dan gagasan.'],
            [2, 37, 'Mengumpulkan, mengklasifikasikan, membandingkan dan memilih informasi dan gagasan dari berbagai sumber.'],
            [3, 37, 'Mengumpulkan, mengklasifikasikan, membandingkan, dan memilih informasi dari berbagai sumber, serta memperjelas informasi dengan bimbingan orang dewasa.'],
            // Subelemen 38
            [1, 38, 'Melakukan penalaran konkret dan memberikan alasan dalam menyelesaikan masalah dan mengambil keputusan.'],
            [2, 38, 'Menjelaskan alasan yang relevan dalam penyelesaian masalah dan pengambilan keputusan.'],
            [3, 38, 'Menjelaskan alasan yang relevan dan akurat dalam penyelesaian masalah dan pengambilan keputusan.'],
            // Subelemen 39
            [1, 39, 'Menyampaikan apa yang sedang dipikirkan secara terperinci.'],
            [2, 39, 'Menyampaikan apa yang sedang dipikirkan dan menjelaskan alasan dari hal yang dipikirkan.'],
            [3, 39, 'Memberikan alasan dari hal yang dipikirkan, serta menyadari kemungkinan adanya bias pada pemikirannya sendiri.'],
            // Subelemen 40
            [1, 40, 'Menggabungkan beberapa gagasan menjadi ide atau gagasan imajinatif yang bermakna untuk mengekspresikan pikiran dan/atau perasaannya.'],
            [2, 40, 'Memunculkan gagasan imajinatif baru yang bermakna dari beberapa gagasan yang berbeda sebagai ekspresi pikiran dan/ atau perasaannya.'],
            [3, 40, 'Mengembangkan gagasan yang ia miliki untuk membuat kombinasi hal yang baru dan imajinatif untuk mengekspresikan pikiran dan/atau perasaannya.'],
            // Subelemen 41
            [1, 41, 'Mengeksplorasi dan mengekspresikan pikiran dan/atau perasaannya dalam bentuk karya dan/ atau tindakan serta mengapresiasi karya dan tindakan yang dihasilkan.'],
            [2, 41, 'Mengeksplorasi dan mengekspresikan pikiran dan/atau perasaannya sesuai dengan minat dan kesukaannya dalam bentuk karya dan/ atau tindakan serta mengapresiasi karya dan tindakan yang dihasilkan.'],
            [3, 41, 'Mengeksplorasi dan mengekspresikan pikiran dan/atau perasaannya sesuai dengan minat dan kesukaannya dalam bentuk karya dan/ atau tindakan serta mengapresiasi dan mengkritisi karya dan tindakan yang dihasilkan.'],
            // Subelemen 42
            [1, 42, 'Mengidentifikasi gagasan-gagasan kreatif untuk menghadapi situasi dan permasalahan.'],
            [2, 42, 'Membandingkan gagasan-gagasan kreatif untuk menghadapi situasi dan permasalahan.'],
            [3, 42, 'Berupaya mencari solusi alternatif saat pendekatan yang diambil tidak berhasil berdasarkan identifikasi terhadap situasi.'],
        ];
        foreach ($capaian as [$faseId, $subElemenId, $nama]) {
            DB::table('p5_capaian_fase')->insert([
                'fase_id' => $faseId,
                'p5_sub_elemen_id' => $subElemenId,
                'capaian' => $nama,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
