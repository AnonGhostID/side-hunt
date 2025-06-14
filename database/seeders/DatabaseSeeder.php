<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use App\Models\KriteriaJob;
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
        // $this->call([
        //     UserSeeder::class,
        //     SideJobSeeder::class,
        // ]);

        Users::factory()->count(40)->create();

        Users::factory()->create([
            'email' => 'a@a',
        ]);

        Users::factory()->count(20)->create();


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

        foreach($hobi_offline as $i){
            KriteriaJob::factory()->create([
                'nama' => ucwords($i)
            ]);
        }
        $path = database_path('seeders/sql/pekerjaans_mass_insert_final.sql');
        DB::unprepared(File::get($path));
    }
}
