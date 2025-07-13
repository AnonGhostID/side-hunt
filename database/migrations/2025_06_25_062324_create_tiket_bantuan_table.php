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
        Schema::create('TiketBantuan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('type', ['bantuan', 'penipuan'])->default('bantuan');
            $table->string('subject');
            $table->text('description');
            $table->enum('status', ['open','done','closed'])->default('open');
            // Fields specific to fraud reports
            $table->string('pihak_terlapor')->nullable();
            $table->date('tanggal_kejadian')->nullable();
            $table->json('bukti_pendukung')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Create ticket messages table for conversation system
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('sender_id');
            $table->enum('sender_type', ['user', 'admin']); 
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->foreign('ticket_id')->references('id')->on('TiketBantuan')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_messages');
        Schema::dropIfExists('TiketBantuan');
    }
};
