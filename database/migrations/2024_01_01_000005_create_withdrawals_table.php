<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('investment_id');
            $table->decimal('principal_amount', 12, 2);
            $table->decimal('net_profit', 12, 2);
            $table->decimal('total_amount', 12, 2);
            $table->string('bank_account');
            $table->string('bank_ifsc');
            $table->string('bank_name');
            $table->string('utr_number')->nullable();    // bank transfer reference
            $table->enum('status', ['pending', 'processing', 'completed', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable(); // admin ID
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('investment_id')->references('id')->on('investments');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
