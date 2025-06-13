<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert admin user
        DB::table('users')->insert([
            'nama' => 'admin',
            'email' => 'admin@example.com',
            'alamat' => 'Jakarta Pusat',
            'telpon' => '081234567890',
            'dompet' => 5000,
            'isAdmin' => 1,
            'password' => bcrypt('admin1234'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert regular users
        DB::table('users')->insert([
            'nama' => 'johndoe',
            'email' => 'orang1@example.com',
            'alamat' => 'Bandung',
            'telpon' => '081298765432',
            'dompet' => 5000,
            'isAdmin' => 0,
            'password' => bcrypt('orang1234'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'nama' => 'janesmith',
            'email' => 'orang2@example.com',
            'alamat' => 'Surabaya',
            'telpon' => '082112345678',
            'dompet' => 5000,
            'isAdmin' => 0,
            'password' => bcrypt('orang1234'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'nama' => 'aguspratama',
            'email' => 'orang3@example.com',
            'alamat' => 'Yogyakarta',
            'telpon' => '083145678901',
            'dompet' => 5000,
            'isAdmin' => 0,
            'password' => bcrypt('orang1234'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert business owners
        DB::table('users')->insert([
            'nama' => 'owner1',
            'email' => 'owner1@example.com',
            'alamat' => 'Semarang',
            'telpon' => '083200000001',
            'dompet' => 5000,
            'isAdmin' => 0,
            'password' => bcrypt('owner1234'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'nama' => 'owner2',
            'email' => 'owner2@example.com',
            'alamat' => 'Malang',
            'telpon' => '083200000002',
            'dompet' => 5000,
            'isAdmin' => 0,
            'password' => bcrypt('owner1234'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'nama' => 'owner3',
            'email' => 'owner3@example.com',
            'alamat' => 'Medan',
            'telpon' => '083200000003',
            'dompet' => 0,
            'isAdmin' => 0,
            'password' => bcrypt('owner1234'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->command->info("Sukses Tambahkan 1 Admin + 6 User Sukses!");
    }
}
