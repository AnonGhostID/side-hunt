<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;

class SideJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $numberOfJobs = 413;
        $userIds = [7, 6, 5];

        // Expanded onsite, physical, daily job types
        $jobTypes = [
            'Pekerja Bangunan',
            'Tukang Kebun',
            'Pengangkat Barang',
            'Kuli Panggul',
            'Pembersih Rumah',
            'Office Boy',
            'Tukang Parkir',
            'Tukang Cat',
            'Petugas Kebersihan',
            'Tukang Angkut',
            'Tenaga Pindahan',
            'Helper Gudang',
            'Kurir Motor Harian',
            'Tukang Kayu Lepas',
            'Asisten Rumah Tangga Harian',
            'Tukang Servis AC (panggilan)',
            'Tukang Listrik Lepas',
            'Montir Panggilan',
            'Pramusaji Acara Harian',
            'Penjaga Stand Bazaar',
            'Pekerja Bongkar Muat',
            'Petugas Parkir Sementara',
            'Tukang Cuci Mobil Keliling',
            'Penjaga Warung Sementara',
            'Kuli Pasar Harian',
            'Tukang Servis Elektronik Panggilan',
            'Penjaga Gudang Sementara',
            'Tukang Las Harian',
            'Tenaga Kebersihan Acara',
            'Crew Pindahan Rumah',
            'Penjaga Area Parkir Event',
            'Anggota Security Lepas',
            'Petugas Set Up Dekorasi',
            'Pekerja Renovasi Rumah',
            'Crew Catering Harian',
            'Penjaga Taman Sementara',
            'Tukang Gali Parit',
            'Tukang Pasang Banner',
            'Tukang Cuci Tangki Air',
            'Crew Produksi UMKM',
            'Tukang Angkat Galon',
            'Petugas Pembersih Saluran Air',
            'Crew Loading Barang Pameran',
            'Tukang Semprot Disinfektan',
            'Tukang Pengecat Marka Jalan',
            'Penjaga Tiket Masuk Event',
            'Tukang Pindah Barang Kantor',
            'Crew Maintenance Gedung Harian',
            'Asisten Pedagang Kaki Lima',
            'Tukang Bersih Karpet Rumah',
            'Petugas Jaga Stand Pameran',
            'Tukang Isi Ulang Air Mineral',
            'Crew Set Up Panggung Acara',
            'Asisten Tukang Jahit Lepas',
            'Penjaga Barang Titipan Sementara',
            'Tukang Cuci Motor Keliling',
            'Petugas Distribusi Brosur',
            'Crew Pembongkaran Dekorasi',
            'Tukang Servis Kulkas Panggilan',
            'Asisten Fotografer Wedding',
            'Petugas Cleaning Service Harian',
            'Tukang Pasang Wallpaper',
            'Crew Catering Hajatan',
            'Penjaga Konter HP Sementara',
            'Tukang Potong Rumput',
            'Asisten Montir Sepeda Motor',
            'Petugas Jaga Parkir Rumah Sakit',
            'Tukang Rakit Furniture',
            'Crew Pemindahan Pameran',
            'Asisten Tukang Roti Harian',
            'Petugas Sortir Barang Bekas',
            'Tukang Cuci Jendela Gedung',
            'Crew Setup Sound System',
            'Asisten Pedagang Pasar Malam',
            'Petugas Jaga Toilet Umum Event',
            'Tukang Bersih Kolam Renang',
            'Crew Distribusi Paket Harian',
            'Asisten Tukang Pijat Panggilan'
        ];

        $cities = [
            ['name' => 'Jakarta Selatan, DKI Jakarta', 'lat' => -6.2615, 'lng' => 106.8106],
            ['name' => 'Jakarta Utara, DKI Jakarta', 'lat' => -6.1389, 'lng' => 106.8636],
            ['name' => 'Jakarta Barat, DKI Jakarta', 'lat' => -6.1668, 'lng' => 106.7658],
            ['name' => 'Jakarta Timur, DKI Jakarta', 'lat' => -6.2250, 'lng' => 106.9004],
            ['name' => 'Jakarta Pusat, DKI Jakarta', 'lat' => -6.1845, 'lng' => 106.8229],
            ['name' => 'Bandung, Jawa Barat', 'lat' => -6.9175, 'lng' => 107.6191],
            ['name' => 'Surabaya, Jawa Timur', 'lat' => -7.2575, 'lng' => 112.7521],
            ['name' => 'Yogyakarta, DI Yogyakarta', 'lat' => -7.7956, 'lng' => 110.3695],
            ['name' => 'Semarang, Jawa Tengah', 'lat' => -6.9667, 'lng' => 110.4167],
            ['name' => 'Medan, Sumatera Utara', 'lat' => 3.5952, 'lng' => 98.6722],
            ['name' => 'Makassar, Sulawesi Selatan', 'lat' => -5.1477, 'lng' => 119.4327],
            ['name' => 'Denpasar, Bali', 'lat' => -8.6500, 'lng' => 115.2167],
            ['name' => 'Tangerang, Banten', 'lat' => -6.1783, 'lng' => 106.6319],
            ['name' => 'Bekasi, Jawa Barat', 'lat' => -6.2383, 'lng' => 106.9756],
            ['name' => 'Depok, Jawa Barat', 'lat' => -6.4058, 'lng' => 106.8142],
            ['name' => 'Malang, Jawa Timur', 'lat' => -7.9797, 'lng' => 112.6304]
        ];

        $sideJobs = [];

        for ($i = 0; $i < $numberOfJobs; $i++) {
            $jobType = $faker->randomElement($jobTypes);
            $city = $faker->randomElement($cities);

            $baseMinSalary = $faker->numberBetween(100000, 500000);
            $maxSalary = $baseMinSalary + $faker->numberBetween(50000, 300000);

            $createdDate = $faker->dateTimeBetween('-30 days', 'now');
            $jobDate = $faker->dateTimeBetween($createdDate, '+7 days');

            $maxWorkers = $faker->numberBetween(1, 10);
            $acceptedApplicants = $faker->numberBetween(0, min($maxWorkers, $faker->numberBetween(0, $maxWorkers + 2)));

            $description = $faker->sentence(10);

            $sideJobs[] = [
                'nama' => $jobType,
                'deskripsi' => $description,
                'tanggal_buat' => $jobDate->format('Y-m-d'),
                'alamat' => $city['name'],
                'koordinat' => $city['lat'] . ', ' . $city['lng'],
                'min_gaji' => $baseMinSalary,
                'max_gaji' => $maxSalary,
                'max_pekerja' => $maxWorkers,
                'jumlah_pelamar_diterima' => $acceptedApplicants,
                'pembuat' => $faker->randomElement($userIds),
                'created_at' => $createdDate,
                'updated_at' => $createdDate,
            ];
        }

        $chunks = array_chunk($sideJobs, 100);
        foreach ($chunks as $chunk) {
            DB::table('side_jobs')->insert($chunk);
        }

        $this->command->info("Successfully created {$numberOfJobs} physical, onsite daily jobs!");
    }
}
