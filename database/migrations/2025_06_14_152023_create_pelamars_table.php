<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pelamars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('job_id');
            $table->dateTime('jadwal_interview')->nullable();
            $table->string('link_Interview')->nullable();
            $table->string('alasan')->nullable();
            $table->integer('gaji_deals')->nullable();
            $table->enum('status', ['tunda', 'interview', 'ditolak','Menunggu Pekerjaan','Sedang Bekerja','Menuggu Pembayaran','Gagal','selesai'])->default('tunda');
            $table->enum('Tipe_Group', ['Sendiri', 'Team'])->default('Sendiri');
            $table->boolean('is_delete')->default(false);
            $table->json('Data_Team')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('job_id')->references('id')->on('pekerjaans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelamar_side_job');
    }
};
