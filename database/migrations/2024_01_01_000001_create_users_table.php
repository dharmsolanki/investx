<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 15)->unique();
            $table->string('password');
            $table->string('pan_number', 10)->nullable();
            $table->string('aadhar_number', 12)->nullable();
            $table->enum('kyc_status', ['pending', 'submitted', 'verified', 'rejected'])->default('pending');
            $table->decimal('wallet_balance', 12, 2)->default(0.00);
            $table->string('bank_account')->nullable();
            $table->string('bank_ifsc')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('referral_code', 10)->unique()->nullable();
            $table->unsignedBigInteger('referred_by')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('referred_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
