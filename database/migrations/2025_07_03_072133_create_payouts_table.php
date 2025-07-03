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
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 12, 2);
            $table->string('bank_code', 10);
            $table->string('account_number', 50);
            $table->string('account_name', 100);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->string('xendit_disbursement_id')->nullable();
            $table->string('xendit_reference_id')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index(['user_id', 'status']);
            $table->index(['created_at']);
            $table->index(['xendit_disbursement_id']);
            
            // Add foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
