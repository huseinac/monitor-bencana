<?php

namespace Database\Seeders;

use App\Models\Indikator;
use App\Models\Pelaksana;
use App\Models\PelaksanaIndikator;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        ini_set('memory_limit', '512M');

        User::create([
            'nama' => 'Super Admin',
            'email' => 'super@admin',
            'password' => bcrypt('SUPERadmin'),
            'akses' => 'Super Admin'
        ]);

        Pelaksana::insert([
            ['nama' => 'Menteri Pendayagunaan Aparatur Negara dan Reformasi Birokrasi', 'singkatan' => 'MenPAN-RB'],
            ['nama' => 'Menteri Desa dan Pembangunan Daerah Tertinggal', 'singkatan' => 'Mendes PDT'],
            ['nama' => 'Menteri Kesehatan', 'singkatan' => 'Menkes'],
            ['nama' => 'Menteri Pendidikan Dasar dan Menengah', 'singkatan' => 'Mendikdasmen'],
            ['nama' => 'Menteri Pekerjaan Umum', 'singkatan' => 'Men PU'],
            ['nama' => 'Gubernur', 'singkatan' => 'Gubernur'],
            ['nama' => 'Bupati / Wali Kota', 'singkatan' => 'Bupati/Walikota'],
            ['nama' => 'Menteri Perdagangan', 'singkatan' => 'Mendag'],
            ['nama' => 'Menteri Usaha Mikro, Kecil, dan Menengah', 'singkatan' => 'Men UMKM'],
            ['nama' => 'Menteri Pariwisata', 'singkatan' => 'Menpar'],
            ['nama' => 'Menteri Agama', 'singkatan' => 'Menag'],
            ['nama' => 'Menteri Sosial', 'singkatan' => 'Mensos'],
            ['nama' => 'PT Pertamina (Persero) / Danantara', 'singkatan' => 'Pertamina/Danantara'],
            ['nama' => 'PT PLN (Persero) / Danantara', 'singkatan' => 'PLN/Danantara'],
            ['nama' => 'Menteri Komunikasi dan Digital', 'singkatan' => 'Komdigi'],
            ['nama' => 'Menteri Energi dan Sumber Daya Mineral', 'singkatan' => 'Men ESDM'],
            ['nama' => 'Menteri Pertanian', 'singkatan' => 'Mentan'],
            ['nama' => 'Menteri Kelautan dan Perikanan', 'singkatan' => 'KKP'],
            ['nama' => 'Badan Nasional Penanggulangan Bencana', 'singkatan' => 'BNPB'],
            ['nama' => 'Menteri Perumahan dan Kawasan Permukiman', 'singkatan' => 'Men PKP'],
        ]);

        Indikator::insert([
            // 1. PEMERINTAHAN
            ['kode' => '01', 'parent_kode' => null, 'nama' => 'Pemerintahan'],
            ['kode' => '01.01', 'parent_kode' => '01', 'nama' => 'Provinsi'],
            ['kode' => '01.02', 'parent_kode' => '01', 'nama' => 'Kabupaten'],
            ['kode' => '01.03', 'parent_kode' => '01', 'nama' => 'Kecamatan'],
            ['kode' => '01.04', 'parent_kode' => '01', 'nama' => 'Desa'],

            // 2. LAYANAN PUBLIK
            ['kode' => '02', 'parent_kode' => null, 'nama' => 'Layanan Publik'],
            ['kode' => '02.01', 'parent_kode' => '02', 'nama' => 'Fasilitas Kesehatan'],
            ['kode' => '02.02', 'parent_kode' => '02', 'nama' => 'Fasilitas Pendidikan'],

            // 3. AKSES DARAT
            ['kode' => '03', 'parent_kode' => null, 'nama' => 'Akses Darat'],
            ['kode' => '03.01', 'parent_kode' => '03', 'nama' => 'Jalan Nasional'],
            ['kode' => '03.02', 'parent_kode' => '03', 'nama' => 'Jalan Provinsi'],
            ['kode' => '03.03', 'parent_kode' => '03', 'nama' => 'Jalan Kabupaten'],
            ['kode' => '03.04', 'parent_kode' => '03', 'nama' => 'Jalan Desa'],
            ['kode' => '03.05', 'parent_kode' => '03', 'nama' => 'Jembatan'],

            // 4. EKONOMI
            ['kode' => '04', 'parent_kode' => null, 'nama' => 'Ekonomi'],
            ['kode' => '04.01', 'parent_kode' => '04', 'nama' => 'Pasar'],
            ['kode' => '04.02', 'parent_kode' => '04', 'nama' => 'Resto Warung / Cafe / Kedai'],
            ['kode' => '04.03', 'parent_kode' => '04', 'nama' => 'Toko Diluar Pasar'],
            ['kode' => '04.04', 'parent_kode' => '04', 'nama' => 'Hotel / Penginapan'],

            // 5. SOSIAL
            ['kode' => '05', 'parent_kode' => null, 'nama' => 'Sosial'],
            ['kode' => '05.01', 'parent_kode' => '05', 'nama' => 'Rumah Ibadah'],
            ['kode' => '05.02', 'parent_kode' => '05', 'nama' => 'Kelompok Rentan'],

            // 6. INDIKATOR DASAR LAIN
            ['kode' => '06', 'parent_kode' => null, 'nama' => 'Indikator Dasar Lain'],
            ['kode' => '06.01', 'parent_kode' => '06', 'nama' => 'SPBU'],
            ['kode' => '06.02', 'parent_kode' => '06', 'nama' => 'Listrik'],
            ['kode' => '06.03', 'parent_kode' => '06', 'nama' => 'PDAM'],
            ['kode' => '06.04', 'parent_kode' => '06', 'nama' => 'Internet'],
            ['kode' => '06.05', 'parent_kode' => '06', 'nama' => 'Gas / Elpiji'],

            // 7. NORMALISASI SUNGAI
            ['kode' => '07', 'parent_kode' => null, 'nama' => 'Normalisasi Sungai'],

            // 8. PERSAWAHAN & PERKEBUNAN
            ['kode' => '08', 'parent_kode' => null, 'nama' => 'Persawahan & Perkebunan'],

            // 9. PERIKANAN (TAMBAK)
            ['kode' => '09', 'parent_kode' => null, 'nama' => 'Perikanan (Tambak)'],

            // 10. PENGUNGSIAN DI TENDA
            ['kode' => '10', 'parent_kode' => null, 'nama' => 'Pengungsian Di Tenda'],

            // 11. PENYALURAN BANTUAN BENCANA
            ['kode' => '11', 'parent_kode' => null, 'nama' => 'Penyaluran Bantuan Bencana'],
            ['kode' => '11.01', 'parent_kode' => '11', 'nama' => 'Huntara'],
            ['kode' => '11.02', 'parent_kode' => '11', 'nama' => 'DTH'],
            ['kode' => '11.03', 'parent_kode' => '11', 'nama' => 'Huntap'],
        ]);

        PelaksanaIndikator::insert([
            // 1. Pemerintahan
            ['indikator_id' => 2, 'pelaksana_id' => 1], // Provinsi -> MenPAN-RB
            ['indikator_id' => 3, 'pelaksana_id' => 1], // Kabupaten -> MenPAN-RB
            ['indikator_id' => 4, 'pelaksana_id' => 1], // Kecamatan -> MenPAN-RB
            ['indikator_id' => 5, 'pelaksana_id' => 2], // Desa -> Mendes PDT

            // 2. Layanan Publik
            ['indikator_id' => 7, 'pelaksana_id' => 3], // Fasilitas Kesehatan -> Menkes
            ['indikator_id' => 8, 'pelaksana_id' => 4], // Fasilitas Pendidikan -> Mendikdasmen

            // 3. Akses Darat
            ['indikator_id' => 10, 'pelaksana_id' => 5], // Jalan Nasional -> Men PU
            ['indikator_id' => 11, 'pelaksana_id' => 6], // Jalan Provinsi -> Gubernur
            ['indikator_id' => 12, 'pelaksana_id' => 7], // Jalan Kabupaten -> Bupati/Walikota
            ['indikator_id' => 13, 'pelaksana_id' => 7], // Jalan Desa -> Bupati/Walikota
            ['indikator_id' => 14, 'pelaksana_id' => 5], // Jembatan -> Men PU

            // 4. Ekonomi
            ['indikator_id' => 16, 'pelaksana_id' => 8], // Pasar -> Mendag
            ['indikator_id' => 17, 'pelaksana_id' => 9], // Resto/Cafe -> Men UMKM
            ['indikator_id' => 18, 'pelaksana_id' => 9], // Toko Diluar Pasar -> Men UMKM
            ['indikator_id' => 19, 'pelaksana_id' => 10], // Hotel -> Menpar

            // 5. Sosial
            ['indikator_id' => 21, 'pelaksana_id' => 11], // Rumah Ibadah -> Menag
            ['indikator_id' => 22, 'pelaksana_id' => 12], // Kelompok Rentan -> Mensos

            // 6. Indikator Dasar Lain
            ['indikator_id' => 24, 'pelaksana_id' => 13], // SPBU -> Pertamina/Danantara
            ['indikator_id' => 25, 'pelaksana_id' => 14], // Listrik -> PLN/Danantara
            ['indikator_id' => 26, 'pelaksana_id' => 5],  // PDAM -> Men PU
            ['indikator_id' => 27, 'pelaksana_id' => 15], // Internet -> Komdigi
            ['indikator_id' => 28, 'pelaksana_id' => 16], // Gas/Elpiji -> Men ESDM

            // 7. Normalisasi Sungai (Point 7)
            ['indikator_id' => 29, 'pelaksana_id' => 5],  // Men PU

            // 8. Persawahan & Perkebunan (Point 8)
            ['indikator_id' => 30, 'pelaksana_id' => 17], // Mentan

            // 9. Perikanan (Point 9)
            ['indikator_id' => 31, 'pelaksana_id' => 18], // KKP

            // 10. Pengungsian Di Tenda (Point 10)
            ['indikator_id' => 32, 'pelaksana_id' => 19], // BNPB

            // 11. Penyaluran Bantuan Bencana
            ['indikator_id' => 34, 'pelaksana_id' => 19], // Huntara -> BNPB
            ['indikator_id' => 34, 'pelaksana_id' => 5],  // Huntara -> Men PU
            ['indikator_id' => 34, 'pelaksana_id' => 13], // Huntara -> Danantara (Pertamina)
            ['indikator_id' => 35, 'pelaksana_id' => 19], // DTH -> BNPB
            ['indikator_id' => 36, 'pelaksana_id' => 20], // Huntap -> Men PKP
        ]);

        $path = public_path('wilayah.json');
        if (!File::exists($path)) {
            $this->command->error("File not found at: $path");
            return;
        }

        $json = File::get($path);
        $data = json_decode($json, true);

        if (!isset($data['RECORDS'])) {
            $this->command->error("Invalid JSON format: 'RECORDS' key missing.");
            return;
        }

        $records = $data['RECORDS'];
        $chunkSize = 1000; // Insert in batches to prevent memory issues

        $this->command->getOutput()->progressStart(count($records));

        DB::beginTransaction();
        try {
            foreach (array_chunk($records, $chunkSize) as $chunk) {
                $insertData = [];
                foreach ($chunk as $row) {
                    $insertData[] = [
                        'kode' => $row['kode'],
                        'parent_kode' => $row['parent_kode'],
                        'nama' => $row['nama'],
                        'polygon' => 'polygons/' . $row['kode'] . '.geojson',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                // Using DB::table for faster bulk insertion
                DB::table('wilayah')->insert($insertData);
                $this->command->getOutput()->progressAdvance(count($chunk));
            }
            DB::commit();
            $this->command->getOutput()->progressFinish();
            $this->command->info("Wilayah data imported successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("Import failed: " . $e->getMessage());
        }
    }
}
