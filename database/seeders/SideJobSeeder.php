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
        $faker = Faker::create('id_ID'); // Indonesian locale for realistic data
        
        // Configuration - you can modify these values
        $numberOfJobs = 323; // Change this to generate more or fewer jobs
        $userIds = [7, 6, 5]; // Array of user IDs that can create jobs
        // $userIds = 5; 

        // Job categories with Indonesian context
        $jobCategories = [
            'technology' => [
                'Web Developer',
                'Mobile App Developer', 
                'UI/UX Designer',
                'Data Analyst',
                'System Administrator',
                'DevOps Engineer',
                'Frontend Developer',
                'Backend Developer',
                'Full Stack Developer',
                'Software Tester',
                'Database Administrator',
                'Cybersecurity Specialist'
            ],
            'design' => [
                'Graphic Designer',
                'Logo Designer',
                'Social Media Designer',
                'Video Editor',
                'Motion Graphics Designer',
                'Illustrator',
                'Product Designer',
                'Brand Designer',
                '3D Designer',
                'Interior Designer'
            ],
            'marketing' => [
                'Content Writer',
                'Copywriter',
                'Social Media Specialist',
                'Digital Marketing Specialist',
                'SEO Specialist',
                'Marketing Consultant',
                'Email Marketing Specialist',
                'Influencer Marketing',
                'Brand Strategist',
                'Marketing Analyst'
            ],
            'other' => [
                'Virtual Assistant',
                'Translator',
                'Data Entry Specialist',
                'Customer Service',
                'Online Tutor',
                'Photographer',
                'Event Organizer',
                'Accountant',
                'Legal Consultant',
                'Project Manager'
            ]
        ];
        
        // Indonesian cities with coordinates
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
            ['name' => 'Palembang, Sumatera Selatan', 'lat' => -2.9761, 'lng' => 104.7754],
            ['name' => 'Samarinda, Kalimantan Timur', 'lat' => -0.5017, 'lng' => 117.1536],
            ['name' => 'Banjarmasin, Kalimantan Selatan', 'lat' => -3.3194, 'lng' => 114.5906],
            ['name' => 'Pekanbaru, Riau', 'lat' => 0.5071, 'lng' => 101.4478],
            ['name' => 'Tangerang, Banten', 'lat' => -6.1783, 'lng' => 106.6319],
            ['name' => 'Bekasi, Jawa Barat', 'lat' => -6.2383, 'lng' => 106.9756],
            ['name' => 'Depok, Jawa Barat', 'lat' => -6.4058, 'lng' => 106.8142],
            ['name' => 'Malang, Jawa Timur', 'lat' => -7.9797, 'lng' => 112.6304]
        ];
        
        $sideJobs = [];
        
        for ($i = 0; $i < $numberOfJobs; $i++) {
            // Select random category and job type
            $category = $faker->randomElement(array_keys($jobCategories));
            $jobType = $faker->randomElement($jobCategories[$category]);
            
            // Select random city
            $city = $faker->randomElement($cities);
            
            // Generate salary range
            $baseMinSalary = $faker->numberBetween(500000, 3000000);
            $maxSalary = $baseMinSalary + $faker->numberBetween(500000, 5000000);
            
            // Generate random date in the past 30 days
            $createdDate = $faker->dateTimeBetween('-30 days', 'now');
            $jobDate = $faker->dateTimeBetween($createdDate, '+7 days');
            
            // Generate max workers and applicants
            $maxWorkers = $faker->numberBetween(1, 10);
            $acceptedApplicants = $faker->numberBetween(0, min($maxWorkers, $faker->numberBetween(0, $maxWorkers + 2)));
            
            // Generate job description based on category
            $descriptions = $this->getJobDescriptions($category, $jobType);
            $description = $faker->randomElement($descriptions);
            
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
        
        // Insert in chunks to avoid memory issues with large datasets
        $chunks = array_chunk($sideJobs, 100);
        foreach ($chunks as $chunk) {
            DB::table('side_jobs')->insert($chunk);
        }
        
        $this->command->info("Successfully created {$numberOfJobs} side jobs!");
    }
    
    /**
     * Get job descriptions based on category
     */
    private function getJobDescriptions($category, $jobType): array
    {
        $descriptions = [
            'technology' => [
                'Mencari developer berpengalaman untuk mengembangkan aplikasi web modern dengan teknologi terkini',
                'Dibutuhkan programmer untuk membuat sistem informasi dengan database yang kompleks',
                'Proyek pengembangan aplikasi mobile dengan fitur real-time dan integrasi API',
                'Membangun website e-commerce dengan sistem pembayaran yang aman dan terintegrasi',
                'Mengembangkan dashboard analitik untuk monitoring data bisnis secara real-time',
                'Membuat aplikasi inventory management dengan teknologi cloud computing',
                'Proyek migrasi sistem legacy ke teknologi modern dan scalable',
                'Pengembangan API RESTful untuk integrasi dengan berbagai platform'
            ],
            'design' => [
                'Desain logo dan brand identity untuk startup teknologi yang sedang berkembang',
                'Membuat desain UI/UX yang menarik dan user-friendly untuk aplikasi mobile',
                'Proyek desain grafis untuk kampanye marketing digital yang kreatif dan engaging',
                'Desain website company profile dengan konsep modern dan responsive',
                'Membuat motion graphics dan animasi untuk video promosi produk',
                'Desain packaging produk dengan konsep yang unik dan eye-catching',
                'Ilustrasi digital untuk buku anak-anak dengan gaya yang colorful dan menarik',
                'Desain booth pameran dan material promosi untuk event trade show'
            ],
            'marketing' => [
                'Menulis konten artikel blog yang SEO-friendly dan engaging untuk website',
                'Mengelola social media dengan strategi content marketing yang efektif',
                'Membuat campaign digital marketing untuk meningkatkan brand awareness',
                'Copywriting untuk landing page dan email marketing campaign',
                'Strategi SEO untuk meningkatkan organic traffic website secara sustainable',
                'Influencer marketing campaign untuk target audience millennial dan gen-z',
                'Content creation untuk berbagai platform social media dengan konsep yang viral',
                'Marketing automation setup untuk lead nurturing dan customer retention'
            ],
            'other' => [
                'Virtual assistant untuk mengelola administrative tasks dan customer support',
                'Translate dokumen bisnis dari bahasa Indonesia ke bahasa Inggris',
                'Data entry untuk database customer dengan tingkat akurasi yang tinggi',
                'Online tutoring untuk mata pelajaran programming dan teknologi',
                'Photography dan videography untuk dokumentasi event corporate',
                'Event planning dan organizing untuk acara launching produk',
                'Accounting dan bookkeeping untuk small business dan startup',
                'Konsultasi legal untuk contract review dan business compliance'
            ]
        ];
        
        return $descriptions[$category] ?? ['Deskripsi pekerjaan yang menarik dan detail'];
    }
}