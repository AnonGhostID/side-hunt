<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('alamat');
            $table->string('telpon')->unique();
            $table->integer('dompet')->default('0');
            $table->boolean('isAdmin')->default('0');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Insert a default user
        DB::table('users')->insert([
            'nama' => 'admin',
            'email' => 'admin@example.com',
            'alamat' => 'Jakarta Pusat',
            'telpon' => '081234567890',
            'dompet' => 20000,
            'isAdmin' => 1,
            'password' => bcrypt('admin1234'), // Use bcrypt for hashing
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'nama' => 'johndoe',
            'email' => 'orang1@example.com',
            'alamat' => 'Bandung',
            'telpon' => '081298765432',
            'dompet' => 15000,
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
            'dompet' => 30000,
            'isAdmin' => 0,
            'password' => bcrypt('orang1234'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'nama' => 'owner1',
            'email' => 'owner1@example.com',
            'alamat' => 'Semarang',
            'telpon' => '083200000001',
            'dompet' => 10000,
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
            'dompet' => 12000,
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
            'dompet' => 14000,
            'isAdmin' => 0,
            'password' => bcrypt('owner1234'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
