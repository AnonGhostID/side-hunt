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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sender')->unsigned();
            $table->bigInteger('receiver')->unsigned();
            $table->longText('contents')->nullable();
            $table->string('file_json')->nullable();
            $table->string('extension')->nullable();
            $table->string('nama_file')->nullable();
            $table->bigInteger('chat_references')->unsigned()->nullable();
            $table->string('body_chat_references')->nullable();
            $table->bigInteger('pekerjaan_id')->unsigned()->nullable();
            $table->string('Lamaran_status')->nullable();
            // $table->string('is_active_job')->nullable();

            $table->enum('status', ['deleted me','sent','read'])->default('sent');
            $table->timestamps();

            $table->foreign('sender')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('receiver')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('chat_references')->references('id')->on('chats')->cascadeOnDelete();
            $table->foreign('pekerjaan_id')->references('id')->on('pekerjaans')->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
