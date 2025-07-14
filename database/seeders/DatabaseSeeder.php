<?php

namespace Database\Seeders;

use App\Http\Controllers\ChatController;
use App\Models\chat;
use App\Models\excel;
use App\Models\to_excel;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use App\Models\Pekerjaan;
use App\Models\KriteriaJob;
use App\Models\Pelamar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = Users::factory()->count(40)->create(
            ['role' => 'user',
                'is_ban' => 1,
            ]
        );



        // dd($user[0]);

        $mitra = Users::factory()->count(10)->create(
            ['role' => 'mitra',
                'is_ban' => 1,
            ]
        );
        // $this->call([
        //     UserSeeder::class,
        //     SideJobSeeder::class,
        // ]);

        // Create admin user
        // Users::factory()->count(40)->create();
        Users::factory()->create([
            'nama' => 'admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'isAdmin' => 1,
            'password' => bcrypt('admin1234'),
        ]);

        // Create user1, user2, user3
        for ($i = 1; $i <= 3; $i++) {
            Users::create([
                'nama' => 'user' . $i,
                'email' => 'user' . $i . '@example.com',
                'role' => 'user',
                'isAdmin' => 0,
                'password' => bcrypt('user1234'),
            ]);
        }

        // Create owner1, owner2, owner3
        for ($i = 1; $i <= 3; $i++) {
            Users::factory()->create([
                'nama' => 'owner' . $i,
                'email' => 'owner' . $i . '@example.com',
                'role' => 'mitra',
                'isAdmin' => 0,
                'password' => bcrypt('owner1234'),
            ]);
        }


        $hobi_offline = [
            "berkebun",
            "memancing",
            "memasak manual",
            "membuat kerajinan tangan",
            "menjahit",
            "memahat kayu",
            "membuat batik",
            "memperbaiki sepeda atau motor",
            "mengelas",
            "membuat kue tradisional",
            "berburu",
            "melukis manual",
            "memotong rambut",
            "membuat sabun",
            "membuat lilin",
            "membangun model miniatur",
            "membuat perhiasan tangan",
            "bersepeda",
            "memperbaiki barang elektronik sederhana",
            "berjalan kaki",
            "membuat sablon manual",
            "mengumpulkan batu atau mineral",
            "memelihara hewan ternak kecil",
            "memancing ikan air tawar",
            "memperbaiki barang rumah tangga",
            "membuat gerabah",
            "memahat batu",
            "memancing dengan alat tradisional",
            "membuat alat musik tradisional",
            "menggambar dengan tangan",
            "memetik buah",
            "mengukir buah atau sayur",
            "memelihara ikan hias",
            "berkemah",
            "membuat lilin aromaterapi",
            "membuat kain tenun",
            "merawat tanaman hias",
            "membuat boneka bahan alami",
            "mengasah pisau",
            "mengolah tanah liat",
            "mengelola kebun bunga",
            "memperbaiki mesin sederhana",
            "menganyam",
            "membuat mainan kayu",
            "membuat topi dari bahan alami",
            "membuat alat rumah tangga dari kayu",
            "membuat lukisan pasir",
            "membuat karya seni daur ulang",
            "berburu jamur liar",
            "membuat makanan fermentasi tradisional"
        ];

        $data_pekerjaan = json_decode(
            file_get_contents(public_path('Dewa/json/data_pekerjaan.json')),
            true
        );

        // dd($data_pekerjaan);


        foreach ($data_pekerjaan as $data) {
            Pekerjaan::factory()->count(1)->create([
                "nama" => $data['nama'],
                "deskripsi" => $data['deskripsi'],
                "alamat" => $data['alamat'],
                "min_gaji" => $data['min_gaji'],
                "max_gaji" => $data['max_gaji'],
                "max_pekerja" => $data['max_pekerja'],
                "jumlah_pelamar_diterima" => $data['jumlah_pelamar_diterima'],
                "is_active" => $data['is_active'],
                "petunjuk_alamat" => $data['petunjuk_alamat'],
                "foto_job" => $data['foto_job'],
                "pembuat" => $mitra[fake()->numberBetween(0, 9)]->id
            ]);
        }




        $all_pekerjaan = Pekerjaan::all();
        foreach ($all_pekerjaan as $pekerjaan) {
            $users = collect(range(0, 39))->shuffle()->take(8)->all();
            // dd($users);
            // dd($pekerjaan);
            foreach ($users as $s) {
                // dd($user[$s]->id);
                $pelamar = Pelamar::factory()->create([
                    'job_id' => $pekerjaan['id'],
                    'user_id' => $user[$s]->id,
                    'status' => 'tunda'
                ]);
                (new ChatController())->update_lamaran($pelamar->id, $user[$s]->id, $pekerjaan['pembuat']);
            }
        }

        $user = Users::factory()->count(1)->create(
            [
                'role' => 'user',
                'email' => 'userban@gmail.com',
                'is_ban' => true

            ]
        );

        $user = Users::factory()->count(1)->create(
            [
                'role' => 'user',
                'email' => 'mitraban@gmail.com',
                'is_ban' => false

            ]
        );
    }
    public function is_own_id($id)
    {
        return session('account')['id'] == $id;
    }
}
