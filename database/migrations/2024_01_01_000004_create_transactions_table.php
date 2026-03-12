<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('investment_id')->nullable();
            $table->enum('type', ['deposit', 'profit', 'commission', 'withdrawal', 'refund', 'referral_bonus']);
            $table->decimal('amount', 12, 2);
            $table->enum('payment_method', ['upi', 'netbanking', 'card', 'imps', 'neft', 'crypto', 'paytm', 'wallet'])->nullable();
            $table->string('payment_id')->nullable();       // Razorpay/gateway transaction ID
            $table->string('gateway_order_id')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->text('notes')->nullable();
            $table->json('gateway_response')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('investment_id')->references('id')->on('investments')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
